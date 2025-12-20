@extends('layouts.app')

@section('title', 'Cooking Log Details')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="mb-4">
        <a href="{{ route('cooking.index') }}" class="text-indigo-600 hover:text-indigo-900">← Back to Cooking Logs</a>
    </div>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h1 class="text-2xl font-semibold text-gray-900">Cooking Log</h1>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Processing Batch</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        <a href="{{ route('processing-batches.show', $cookingLog->processingBatch) }}" class="text-indigo-600 hover:text-indigo-900">{{ $cookingLog->processingBatch->batch_number }}</a>
                    </dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Cooked At</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $cookingLog->cooked_at->format('F d, Y H:i') }}</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Quantity Cooked</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $cookingLog->quantity_cooked }} units</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Temperature</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $cookingLog->cooking_temperature_celsius }}°C</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Duration</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $cookingLog->cooking_duration_minutes }} minutes</dd>
                </div>
                @if($cookingLog->order)
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Order</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        <a href="{{ route('orders.show', $cookingLog->order) }}" class="text-indigo-600 hover:text-indigo-900">{{ $cookingLog->order->order_number }}</a>
                    </dd>
                </div>
                @endif
            </dl>
        </div>
    </div>
</div>
@endsection

