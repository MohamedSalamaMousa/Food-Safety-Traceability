@extends('layouts.app')

@section('title', 'Processing Batch Details')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="mb-4">
        <a href="{{ route('processing-batches.index') }}" class="text-indigo-600 hover:text-indigo-900">← Back to Processing Batches</a>
    </div>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
            <h1 class="text-2xl font-semibold text-gray-900">Kofta Batch: {{ $batch->batch_number }}</h1>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Raw Meat Batch</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        <a href="{{ route('raw-meat-batches.show', $batch->rawMeatBatch) }}" class="text-indigo-600 hover:text-indigo-900">{{ $batch->rawMeatBatch->batch_number }}</a>
                        <span class="text-gray-500"> from {{ $batch->rawMeatBatch->supplier->name }}</span>
                    </dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Production Date</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $batch->production_date->format('F d, Y') }}</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Quantity</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $batch->quantity_units }} units</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 bg-green-100 text-green-800">{{ ucfirst($batch->status) }}</span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
    @if($batch->cookingLogs->count() > 0)
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h2 class="text-lg font-medium text-gray-900">Cooking Logs</h2>
        </div>
        <div class="px-4 py-5">
            <ul class="divide-y divide-gray-200">
                @foreach($batch->cookingLogs as $log)
                <li class="py-3">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Cooked: {{ $log->cooked_at->format('M d, Y H:i') }}</p>
                            <p class="text-sm text-gray-500">Quantity: {{ $log->quantity_cooked }} units at {{ $log->cooking_temperature_celsius }}°C</p>
                        </div>
                        @if($log->order)
                        <a href="{{ route('orders.show', $log->order) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">Order: {{ $log->order->order_number }}</a>
                        @endif
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
</div>
@endsection


