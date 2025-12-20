<?php

namespace App\Http\Controllers;

use App\Models\RawMeatBatch;
use App\Models\Supplier;
use App\Models\StorageLog;
use Illuminate\Http\Request;

class RawMeatBatchController extends Controller
{
    /**
     * Display a listing of raw meat batches
     */
    public function index()
    {
        $batches = RawMeatBatch::with('supplier')
            ->latest()
            ->paginate(15);
        
        return view('raw-meat-batches.index', compact('batches'));
    }

    /**
     * Show the form for creating a new raw meat batch
     */
    public function create()
    {
        $suppliers = Supplier::where('status', 'active')->get();
        return view('raw-meat-batches.create', compact('suppliers'));
    }

    /**
     * Store a newly created raw meat batch
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'batch_number' => 'required|string|max:255|unique:raw_meat_batches',
            'supplier_id' => 'required|exists:suppliers,id',
            'production_date' => 'required|date',
            'expiration_date' => 'required|date|after:production_date',
            'quantity_kg' => 'required|numeric|min:0',
            'status' => 'required|in:received,in_storage,processing,expired,used',
            'notes' => 'nullable|string',
        ]);

        RawMeatBatch::create($validated);

        return redirect()->route('raw-meat-batches.index')
            ->with('success', 'Raw meat batch created successfully.');
    }

    /**
     * Display the specified raw meat batch with full lifecycle traceability
     */
    public function show(string $id)
    {
        $batch = RawMeatBatch::with([
            'supplier',
            'storageLogs' => function($query) {
                $query->latest('logged_at');
            },
            'processingBatches.cookingLogs.order'
        ])->findOrFail($id);
        
        return view('raw-meat-batches.show', compact('batch'));
    }

    /**
     * Show the form for editing the specified raw meat batch
     */
    public function edit(string $id)
    {
        $batch = RawMeatBatch::findOrFail($id);
        $suppliers = Supplier::where('status', 'active')->get();
        return view('raw-meat-batches.edit', compact('batch', 'suppliers'));
    }

    /**
     * Update the specified raw meat batch
     */
    public function update(Request $request, string $id)
    {
        $batch = RawMeatBatch::findOrFail($id);

        $validated = $request->validate([
            'batch_number' => 'required|string|max:255|unique:raw_meat_batches,batch_number,' . $id,
            'supplier_id' => 'required|exists:suppliers,id',
            'production_date' => 'required|date',
            'expiration_date' => 'required|date|after:production_date',
            'quantity_kg' => 'required|numeric|min:0',
            'status' => 'required|in:received,in_storage,processing,expired,used',
            'notes' => 'nullable|string',
        ]);

        $batch->update($validated);

        return redirect()->route('raw-meat-batches.index')
            ->with('success', 'Raw meat batch updated successfully.');
    }

    /**
     * Remove the specified raw meat batch
     */
    public function destroy(string $id)
    {
        $batch = RawMeatBatch::findOrFail($id);
        $batch->delete();

        return redirect()->route('raw-meat-batches.index')
            ->with('success', 'Raw meat batch deleted successfully.');
    }

    /**
     * Log storage temperature for a raw meat batch
     */
    public function logStorage(Request $request, string $id)
    {
        $batch = RawMeatBatch::findOrFail($id);

        $validated = $request->validate([
            'temperature_celsius' => 'required|numeric',
            'humidity_percentage' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'logged_at' => 'nullable|date',
        ]);

        $validated['raw_meat_batch_id'] = $batch->id;
        $validated['logged_at'] = $validated['logged_at'] ?? now();

        StorageLog::create($validated);

        return redirect()->route('raw-meat-batches.show', $batch->id)
            ->with('success', 'Storage log created successfully.');
    }
}
