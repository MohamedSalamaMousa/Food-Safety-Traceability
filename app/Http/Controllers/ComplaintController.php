<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Order;
use App\Models\ProcessingBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ComplaintController extends Controller
{
    /**
     * Display a listing of complaints
     */
    public function index(Request $request)
    {
        $query = Complaint::with(['order', 'processingBatch']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('complaint_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('symptoms', 'like', "%{$search}%");
            });
        }

        // Filter by severity
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $complaints = $query->latest('incident_date')->paginate(15)->withQueryString();
        
        // Calculate stats for display
        $stats = [
            'total' => Complaint::count(),
            'active' => Complaint::whereIn('status', ['reported', 'investigating'])->count(),
            'critical' => Complaint::where('severity', 'critical')->count(),
            'resolved' => Complaint::where('status', 'resolved')->count(),
        ];
        
        return view('complaints.index', compact('complaints', 'stats'));
    }

    /**
     * Show the form for creating a new complaint
     */
    public function create()
    {
        // Show all orders, prioritizing served ones
        $orders = Order::latest('served_at')
            ->latest('created_at')
            ->get();
        
        $processingBatches = ProcessingBatch::whereIn('status', ['cooking', 'sold', 'ready'])
            ->get();
        
        return view('complaints.create', compact('orders', 'processingBatches'));
    }

    /**
     * Store a newly created complaint
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'processing_batch_id' => 'nullable|exists:processing_batches,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:255',
            'symptoms' => 'required|string',
            'incident_description' => 'required|string',
            'incident_date' => 'required|date',
            'severity' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:reported,investigating,resolved,closed',
            'investigation_notes' => 'nullable|string',
        ]);

        // Generate unique complaint number
        $validated['complaint_number'] = 'COMP-' . strtoupper(Str::random(8));

        $complaint = Complaint::create($validated);

        return redirect()->route('complaints.show', $complaint->id)
            ->with('success', 'Complaint registered successfully.');
    }

    /**
     * Display the specified complaint with FULL TRACEABILITY
     * This is the most important feature - trace-back from complaint to supplier
     */
    public function show(string $id)
    {
        $complaint = Complaint::with([
            'order.orderItems.cookingLog.processingBatch.rawMeatBatch.supplier',
            'order.orderItems.cookingLog.processingBatch.rawMeatBatch.storageLogs' => function($query) {
                $query->latest('logged_at');
            },
            'processingBatch.rawMeatBatch.supplier',
            'processingBatch.rawMeatBatch.storageLogs' => function($query) {
                $query->latest('logged_at');
            }
        ])->findOrFail($id);

        // Build complete traceability chain
        $traceability = $this->buildTraceability($complaint);
        
        return view('complaints.show', compact('complaint', 'traceability'));
    }

    /**
     * Build complete traceability chain from complaint to supplier
     * This method traces back through the entire lifecycle
     */
    private function buildTraceability(Complaint $complaint)
    {
        $trace = [
            'complaint' => $complaint,
            'order' => null,
            'order_items' => [],
            'cooking_logs' => [],
            'processing_batches' => [],
            'raw_meat_batches' => [],
            'suppliers' => [],
            'storage_logs' => [],
            'timeline' => [],
        ];

        // Get order information
        if ($complaint->order) {
            $trace['order'] = $complaint->order;
            
            // Get all order items and their cooking logs
            foreach ($complaint->order->orderItems as $orderItem) {
                $trace['order_items'][] = $orderItem;
                
                if ($orderItem->cookingLog) {
                    $cookingLog = $orderItem->cookingLog;
                    $trace['cooking_logs'][] = $cookingLog;
                    
                    // Get processing batch
                    if ($cookingLog->processingBatch) {
                        $processingBatch = $cookingLog->processingBatch;
                        $trace['processing_batches'][] = $processingBatch;
                        
                        // Get raw meat batch
                        if ($processingBatch->rawMeatBatch) {
                            $rawMeatBatch = $processingBatch->rawMeatBatch;
                            $trace['raw_meat_batches'][] = $rawMeatBatch;
                            
                            // Get supplier
                            if ($rawMeatBatch->supplier) {
                                $trace['suppliers'][] = $rawMeatBatch->supplier;
                            }
                            
                            // Get storage logs
                            foreach ($rawMeatBatch->storageLogs as $storageLog) {
                                $trace['storage_logs'][] = $storageLog;
                            }
                        }
                    }
                }
            }
        }

        // Also check if complaint has direct processing batch reference
        if ($complaint->processingBatch) {
            $processingBatch = $complaint->processingBatch;
            if (!in_array($processingBatch, $trace['processing_batches'])) {
                $trace['processing_batches'][] = $processingBatch;
                
                if ($processingBatch->rawMeatBatch) {
                    $rawMeatBatch = $processingBatch->rawMeatBatch;
                    if (!in_array($rawMeatBatch, $trace['raw_meat_batches'])) {
                        $trace['raw_meat_batches'][] = $rawMeatBatch;
                        
                        if ($rawMeatBatch->supplier && !in_array($rawMeatBatch->supplier, $trace['suppliers'])) {
                            $trace['suppliers'][] = $rawMeatBatch->supplier;
                        }
                    }
                }
            }
        }

        // Build timeline
        $trace['timeline'] = $this->buildTimeline($trace);

        // Remove duplicates
        $trace['cooking_logs'] = array_unique($trace['cooking_logs'], SORT_REGULAR);
        $trace['processing_batches'] = array_unique($trace['processing_batches'], SORT_REGULAR);
        $trace['raw_meat_batches'] = array_unique($trace['raw_meat_batches'], SORT_REGULAR);
        $trace['suppliers'] = array_unique($trace['suppliers'], SORT_REGULAR);

        return $trace;
    }

    /**
     * Build a chronological timeline of events
     */
    private function buildTimeline(array $trace)
    {
        $timeline = [];

        // Add supplier delivery (raw meat batch production date)
        foreach ($trace['raw_meat_batches'] as $rawMeatBatch) {
            $timeline[] = [
                'date' => $rawMeatBatch->production_date,
                'type' => 'supplier_delivery',
                'title' => 'Raw Meat Received from Supplier',
                'description' => "Batch: {$rawMeatBatch->batch_number} ({$rawMeatBatch->quantity_kg} kg) from {$rawMeatBatch->supplier->name}",
                'data' => $rawMeatBatch,
            ];
        }

        // Add storage logs
        foreach ($trace['storage_logs'] as $storageLog) {
            $timeline[] = [
                'date' => $storageLog->logged_at,
                'type' => 'storage_log',
                'title' => 'Storage Temperature Logged',
                'description' => "Temperature: {$storageLog->temperature_celsius}°C",
                'data' => $storageLog,
            ];
        }

        // Add processing batch creation
        foreach ($trace['processing_batches'] as $processingBatch) {
            $timeline[] = [
                'date' => $processingBatch->production_date,
                'type' => 'processing',
                'title' => 'Kofta Batch Created',
                'description' => "Batch: {$processingBatch->batch_number} ({$processingBatch->quantity_units} units)",
                'data' => $processingBatch,
            ];
        }

        // Add cooking logs
        foreach ($trace['cooking_logs'] as $cookingLog) {
            $timeline[] = [
                'date' => $cookingLog->cooked_at,
                'type' => 'cooking',
                'title' => 'Kofta Cooked',
                'description' => "Quantity: {$cookingLog->quantity_cooked} units at {$cookingLog->cooking_temperature_celsius}°C",
                'data' => $cookingLog,
            ];
        }

        // Add order served
        if ($trace['order'] && $trace['order']->served_at) {
            $timeline[] = [
                'date' => $trace['order']->served_at,
                'type' => 'order_served',
                'title' => 'Order Served to Customer',
                'description' => "Order: {$trace['order']->order_number}",
                'data' => $trace['order'],
            ];
        }

        // Add complaint incident
        $timeline[] = [
            'date' => $trace['complaint']->incident_date,
            'type' => 'complaint',
            'title' => 'Food Poisoning Incident Reported',
            'description' => "Complaint: {$trace['complaint']->complaint_number} - {$trace['complaint']->symptoms}",
            'data' => $trace['complaint'],
        ];

        // Sort timeline by date
        usort($timeline, function($a, $b) {
            return $a['date'] <=> $b['date'];
        });

        return $timeline;
    }

    /**
     * Show the form for editing the specified complaint
     */
    public function edit(string $id)
    {
        $complaint = Complaint::findOrFail($id);
        $orders = Order::all();
        $processingBatches = ProcessingBatch::all();
        
        return view('complaints.edit', compact('complaint', 'orders', 'processingBatches'));
    }

    /**
     * Update the specified complaint
     */
    public function update(Request $request, string $id)
    {
        $complaint = Complaint::findOrFail($id);

        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'processing_batch_id' => 'nullable|exists:processing_batches,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:255',
            'symptoms' => 'required|string',
            'incident_description' => 'required|string',
            'incident_date' => 'required|date',
            'severity' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:reported,investigating,resolved,closed',
            'investigation_notes' => 'nullable|string',
        ]);

        $complaint->update($validated);

        return redirect()->route('complaints.show', $complaint->id)
            ->with('success', 'Complaint updated successfully.');
    }

    /**
     * Remove the specified complaint
     */
    public function destroy(string $id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->delete();

        return redirect()->route('complaints.index')
            ->with('success', 'Complaint deleted successfully.');
    }
}
