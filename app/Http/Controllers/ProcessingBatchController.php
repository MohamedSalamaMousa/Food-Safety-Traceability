<?php

namespace App\Http\Controllers;

use App\Models\ProcessingBatch;
use App\Models\RawMeatBatch;
use Illuminate\Http\Request;

class ProcessingBatchController extends Controller
{
    /**
     * Display a listing of processing batches
     */
    public function index()
    {
        $batches = ProcessingBatch::with(['rawMeatBatch.supplier'])
            ->latest()
            ->paginate(15);
        
        return view('processing-batches.index', compact('batches'));
    }

    /**
     * Show the form for creating a new processing batch
     */
    public function create()
    {
        $rawMeatBatches = RawMeatBatch::whereIn('status', ['received', 'in_storage'])
            ->get();
        
        return view('processing-batches.create', compact('rawMeatBatches'));
    }

    /**
     * Store a newly created processing batch
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'batch_number' => 'required|string|max:255|unique:processing_batches',
            'raw_meat_batch_id' => 'required|exists:raw_meat_batches,id',
            'production_date' => 'required|date',
            'expiration_date' => 'required|date|after:production_date',
            'quantity_units' => 'required|integer|min:1',
            'status' => 'required|in:processing,ready,cooking,sold,expired,wasted',
            'notes' => 'nullable|string',
        ]);

        $processingBatch = ProcessingBatch::create($validated);

        // Update raw meat batch status to processing
        $rawMeatBatch = RawMeatBatch::findOrFail($validated['raw_meat_batch_id']);
        if ($rawMeatBatch->status === 'in_storage' || $rawMeatBatch->status === 'received') {
            $rawMeatBatch->update(['status' => 'processing']);
        }

        return redirect()->route('processing-batches.index')
            ->with('success', 'Processing batch created successfully.');
    }

    /**
     * Display the specified processing batch with full traceability
     */
    public function show(string $id)
    {
        $batch = ProcessingBatch::with([
            'rawMeatBatch.supplier',
            'rawMeatBatch.storageLogs' => function($query) {
                $query->latest('logged_at')->limit(10);
            },
            'cookingLogs.order.orderItems'
        ])->findOrFail($id);
        
        return view('processing-batches.show', compact('batch'));
    }

    /**
     * Show the form for editing the specified processing batch
     */
    public function edit(string $id)
    {
        $batch = ProcessingBatch::findOrFail($id);
        $rawMeatBatches = RawMeatBatch::whereIn('status', ['received', 'in_storage', 'processing'])
            ->get();
        
        return view('processing-batches.edit', compact('batch', 'rawMeatBatches'));
    }

    /**
     * Update the specified processing batch
     */
    public function update(Request $request, string $id)
    {
        $batch = ProcessingBatch::findOrFail($id);

        $validated = $request->validate([
            'batch_number' => 'required|string|max:255|unique:processing_batches,batch_number,' . $id,
            'raw_meat_batch_id' => 'required|exists:raw_meat_batches,id',
            'production_date' => 'required|date',
            'expiration_date' => 'required|date|after:production_date',
            'quantity_units' => 'required|integer|min:1',
            'status' => 'required|in:processing,ready,cooking,sold,expired,wasted',
            'notes' => 'nullable|string',
        ]);

        $batch->update($validated);

        return redirect()->route('processing-batches.index')
            ->with('success', 'Processing batch updated successfully.');
    }

    /**
     * Remove the specified processing batch
     */
    public function destroy(string $id)
    {
        $batch = ProcessingBatch::findOrFail($id);
        $batch->delete();

        return redirect()->route('processing-batches.index')
            ->with('success', 'Processing batch deleted successfully.');
    }
}
