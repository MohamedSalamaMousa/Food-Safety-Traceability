<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CookingLog;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index()
    {
        $orders = Order::with(['orderItems.cookingLog.processingBatch'])
            ->latest()
            ->paginate(15);
        
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order
     */
    public function create()
    {
        $cookingLogs = CookingLog::whereHas('processingBatch', function($query) {
            $query->whereIn('status', ['ready', 'cooking', 'sold']);
        })
        ->with('processingBatch')
        ->latest('cooked_at')
        ->get();
        
        return view('orders.create', compact('cookingLogs'));
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'required|string|max:255|unique:orders',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:255',
            'status' => 'required|in:pending,preparing,ready,served,cancelled',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.cooking_log_id' => 'required|exists:cooking_logs,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'nullable|numeric|min:0',
        ], [
            'items.required' => 'At least one order item is required.',
            'items.min' => 'At least one order item is required.',
            'items.*.cooking_log_id.required' => 'Please select a cooking log for each item.',
            'items.*.cooking_log_id.exists' => 'The selected cooking log is invalid.',
            'items.*.quantity.required' => 'Quantity is required for each item.',
            'items.*.quantity.min' => 'Quantity must be at least 1.',
        ]);

        $order = Order::create([
            'order_number' => $validated['order_number'],
            'customer_name' => $validated['customer_name'] ?? null,
            'customer_phone' => $validated['customer_phone'] ?? null,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // Create order items
        foreach ($validated['items'] as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'cooking_log_id' => $item['cooking_log_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'] ?? null,
            ]);
        }

        return redirect()->route('orders.index')
            ->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified order with full traceability
     */
    public function show(string $id)
    {
        $order = Order::with([
            'orderItems.cookingLog.processingBatch.rawMeatBatch.supplier',
            'orderItems.cookingLog.processingBatch.rawMeatBatch.storageLogs' => function($query) {
                $query->latest('logged_at')->limit(10);
            },
            'complaints'
        ])->findOrFail($id);
        
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order
     */
    public function edit(string $id)
    {
        $order = Order::with('orderItems.cookingLog')->findOrFail($id);
        $cookingLogs = CookingLog::whereHas('processingBatch', function($query) {
            $query->whereIn('status', ['ready', 'cooking', 'sold']);
        })
        ->with('processingBatch')
        ->latest('cooked_at')
        ->get();
        
        return view('orders.edit', compact('order', 'cookingLogs'));
    }

    /**
     * Update the specified order
     */
    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'order_number' => 'required|string|max:255|unique:orders,order_number,' . $id,
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:255',
            'status' => 'required|in:pending,preparing,ready,served,cancelled',
            'served_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Auto-set served_at when status changes to served
        if ($validated['status'] === 'served' && !$order->served_at) {
            $validated['served_at'] = now();
        }

        $order->update($validated);

        return redirect()->route('orders.index')
            ->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified order
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}
