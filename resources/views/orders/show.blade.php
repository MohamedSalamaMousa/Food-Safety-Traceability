@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="mb-4">
        <a href="{{ route('orders.index') }}" class="text-indigo-600 hover:text-indigo-900">← Back to Orders</a>
    </div>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
            <h1 class="text-2xl font-semibold text-gray-900">Order: {{ $order->order_number }}</h1>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Customer</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $order->customer_name ?? 'N/A' }}</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Served At</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $order->served_at ? $order->served_at->format('F d, Y H:i') : 'N/A' }}</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 bg-blue-100 text-blue-800">{{ ucfirst($order->status) }}</span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
    @if($order->orderItems->count() > 0)
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
            <h2 class="text-lg font-medium text-gray-900">Order Items</h2>
        </div>
        <div class="px-4 py-5">
            <ul class="divide-y divide-gray-200">
                @foreach($order->orderItems as $item)
                <li class="py-3">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Quantity: {{ $item->quantity }} units</p>
                            <p class="text-sm text-gray-500">From Processing Batch: {{ $item->cookingLog->processingBatch->batch_number }}</p>
                            <p class="text-sm text-gray-500">Cooked: {{ $item->cookingLog->cooked_at->format('M d, Y H:i') }} at {{ $item->cookingLog->cooking_temperature_celsius }}°C</p>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
    @if($order->complaints->count() > 0)
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-red-200">
            <h2 class="text-lg font-medium text-red-900">⚠️ Complaints Related to This Order</h2>
        </div>
        <div class="px-4 py-5">
            <ul class="divide-y divide-gray-200">
                @foreach($order->complaints as $complaint)
                <li class="py-3">
                    <a href="{{ route('complaints.show', $complaint) }}" class="text-red-600 hover:text-red-900 font-medium">{{ $complaint->complaint_number }}</a>
                    <p class="text-sm text-gray-500">{{ $complaint->symptoms }}</p>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
</div>
@endsection

