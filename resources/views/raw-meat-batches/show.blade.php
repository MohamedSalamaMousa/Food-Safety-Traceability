@extends('layouts.app')

@section('title', 'Raw Meat Batch Details')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="mb-4">
        <a href="{{ route('raw-meat-batches.index') }}" class="text-indigo-600 hover:text-indigo-900">← Back to Raw Meat Batches</a>
    </div>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
            <h1 class="text-2xl font-semibold text-gray-900">Batch: {{ $batch->batch_number }}</h1>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Supplier</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $batch->supplier->name }}</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Production Date</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $batch->production_date->format('F d, Y') }}</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Expiration Date</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $batch->expiration_date->format('F d, Y') }}</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Quantity</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $batch->quantity_kg }} kg</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 bg-yellow-100 text-yellow-800">{{ ucfirst($batch->status) }}</span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
    @if($batch->storageLogs->count() > 0)
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
            <h2 class="text-lg font-medium text-gray-900">Storage Logs</h2>
        </div>
        <div class="px-4 py-5">
            <div class="space-y-4">
                @foreach($batch->storageLogs->take(10) as $log)
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm"><strong>Date:</strong> {{ $log->logged_at->format('M d, Y H:i') }}</p>
                    <p class="text-sm"><strong>Temperature:</strong> {{ $log->temperature_celsius }}°C</p>
                    @if($log->humidity_percentage)
                    <p class="text-sm"><strong>Humidity:</strong> {{ $log->humidity_percentage }}%</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
    @if($batch->processingBatches->count() > 0)
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h2 class="text-lg font-medium text-gray-900">Processing Batches</h2>
        </div>
        <div class="px-4 py-5">
            <ul class="divide-y divide-gray-200">
                @foreach($batch->processingBatches as $pbatch)
                <li class="py-3">
                    <a href="{{ route('processing-batches.show', $pbatch) }}" class="text-indigo-600 hover:text-indigo-900">{{ $pbatch->batch_number }}</a>
                    <span class="text-sm text-gray-500">({{ $pbatch->quantity_units }} units)</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
</div>
@endsection

