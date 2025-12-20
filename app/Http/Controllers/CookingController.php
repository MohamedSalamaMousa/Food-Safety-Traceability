<?php

namespace App\Http\Controllers;

use App\Models\CookingLog;
use App\Models\ProcessingBatch;
use App\Models\Order;
use Illuminate\Http\Request;

class CookingController extends Controller
{
    /**
     * Display a listing of cooking logs
     */
    public function index()
    {
        $cookingLogs = CookingLog::with(['processingBatch.rawMeatBatch.supplier', 'order'])
            ->latest('cooked_at')
            ->paginate(15);
        
        return view('cooking.index', compact('cookingLogs'));
    }

    /**
     * Show the form for creating a new cooking log
     */
    public function create()
    {
        $processingBatches = ProcessingBatch::whereIn('status', ['ready', 'cooking'])
            ->get();
        
        $orders = Order::whereIn('status', ['pending', 'preparing'])
            ->get();
        
        return view('cooking.create', compact('processingBatches', 'orders'));
    }

    /**
     * Store a newly created cooking log
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'processing_batch_id' => 'required|exists:processing_batches,id',
            'order_id' => 'nullable|exists:orders,id',
            'quantity_cooked' => 'required|integer|min:1',
            'cooking_temperature_celsius' => 'required|numeric|min:0',
            'cooking_duration_minutes' => 'required|integer|min:1',
            'cooked_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Handle nullable fields - convert empty strings to null
        if (empty($validated['order_id'])) {
            $validated['order_id'] = null;
        }
        
        $validated['cooked_at'] = $validated['cooked_at'] ?? now();

        $cookingLog = CookingLog::create($validated);

        // Update processing batch status
        $processingBatch = ProcessingBatch::findOrFail($validated['processing_batch_id']);
        if ($processingBatch->status === 'ready') {
            $processingBatch->update(['status' => 'cooking']);
        }

        // Update order status if provided
        if (isset($validated['order_id']) && $validated['order_id']) {
            $order = Order::findOrFail($validated['order_id']);
            if ($order->status === 'pending') {
                $order->update(['status' => 'preparing']);
            }
        }

        return redirect()->route('cooking.index')
            ->with('success', 'Cooking log created successfully.');
    }

    /**
     * Display the specified cooking log with traceability
     */
    public function show(string $id)
    {
        $cookingLog = CookingLog::with([
            'processingBatch.rawMeatBatch.supplier',
            'processingBatch.rawMeatBatch.storageLogs' => function($query) {
                $query->latest('logged_at')->limit(10);
            },
            'order.orderItems'
        ])->findOrFail($id);
        
        return view('cooking.show', compact('cookingLog'));
    }

    /**
     * Show the form for editing the specified cooking log
     */
    public function edit(string $id)
    {
        $cookingLog = CookingLog::findOrFail($id);
        $processingBatches = ProcessingBatch::whereIn('status', ['ready', 'cooking', 'sold'])
            ->get();
        $orders = Order::all();
        
        return view('cooking.edit', compact('cookingLog', 'processingBatches', 'orders'));
    }

    /**
     * Update the specified cooking log
     */
    public function update(Request $request, string $id)
    {
        $cookingLog = CookingLog::findOrFail($id);

        $validated = $request->validate([
            'processing_batch_id' => 'required|exists:processing_batches,id',
            'order_id' => 'nullable|exists:orders,id',
            'quantity_cooked' => 'required|integer|min:1',
            'cooking_temperature_celsius' => 'required|numeric|min:0',
            'cooking_duration_minutes' => 'required|integer|min:1',
            'cooked_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $cookingLog->update($validated);

        return redirect()->route('cooking.index')
            ->with('success', 'Cooking log updated successfully.');
    }

    /**
     * Remove the specified cooking log
     */
    public function destroy(string $id)
    {
        $cookingLog = CookingLog::findOrFail($id);
        $cookingLog->delete();

        return redirect()->route('cooking.index')
            ->with('success', 'Cooking log deleted successfully.');
    }
}
