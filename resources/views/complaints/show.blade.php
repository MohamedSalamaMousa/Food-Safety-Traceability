@extends('layouts.app')

@section('title', 'Complaint Traceability Report')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="mb-4 flex items-center justify-between">
        <a href="{{ route('complaints.index') }}" class="text-indigo-600 hover:text-indigo-900 flex items-center">
            <span class="mr-2">←</span> Back to Complaints
        </a>
        <a href="{{ route('export.complaint', $complaint->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <span class="mr-2">📥</span> Export Report
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-lg p-4 shadow-sm">
            <div class="flex items-center">
                <div class="text-3xl mr-3">🚨</div>
                <div>
                    <p class="text-xs font-medium text-red-600 uppercase">Severity</p>
                    <p class="text-lg font-bold text-red-900">{{ ucfirst($complaint->severity) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-4 shadow-sm">
            <div class="flex items-center">
                <div class="text-3xl mr-3">📋</div>
                <div>
                    <p class="text-xs font-medium text-blue-600 uppercase">Order</p>
                    <p class="text-lg font-bold text-blue-900">{{ $complaint->order->order_number ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-4 shadow-sm">
            <div class="flex items-center">
                <div class="text-3xl mr-3">👤</div>
                <div>
                    <p class="text-xs font-medium text-purple-600 uppercase">Customer</p>
                    <p class="text-lg font-bold text-purple-900">{{ $complaint->customer_name }}</p>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-lg p-4 shadow-sm">
            <div class="flex items-center">
                <div class="text-3xl mr-3">📅</div>
                <div>
                    <p class="text-xs font-medium text-orange-600 uppercase">Incident Date</p>
                    <p class="text-lg font-bold text-orange-900">{{ $complaint->incident_date->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Complaint Header -->
    <div class="bg-white shadow-lg overflow-hidden sm:rounded-lg mb-6 border-t-4 border-red-500">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-gradient-to-r from-red-50 to-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Complaint: {{ $complaint->complaint_number }}</h1>
                    <p class="mt-1 text-sm text-gray-600">Food Poisoning Incident Report & Traceability</p>
                </div>
                <div class="flex gap-2">
                    <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold leading-5 
                        {{ $complaint->severity === 'critical' ? 'bg-red-100 text-red-800 border-2 border-red-300' : 
                           ($complaint->severity === 'high' ? 'bg-orange-100 text-orange-800 border-2 border-orange-300' : 
                           ($complaint->severity === 'medium' ? 'bg-yellow-100 text-yellow-800 border-2 border-yellow-300' : 'bg-green-100 text-green-800 border-2 border-green-300')) }}">
                        {{ ucfirst($complaint->severity) }} Severity
                    </span>
                    <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold leading-5 bg-blue-100 text-blue-800 border-2 border-blue-300">
                        {{ ucfirst($complaint->status) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Customer</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $complaint->customer_name }} ({{ $complaint->customer_phone }})</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Incident Date</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $complaint->incident_date->format('F d, Y H:i') }}</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Symptoms</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $complaint->symptoms }}</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $complaint->incident_description }}</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 bg-blue-100 text-blue-800">
                            {{ ucfirst($complaint->status) }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- TRACEABILITY TIMELINE - MOST IMPORTANT FEATURE -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-blue-50">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">🔍 Complete Traceability Timeline</h2>
                    <p class="mt-1 text-sm text-gray-600">Full lifecycle from supplier to customer - Every step tracked</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500">Total Events</p>
                    <p class="text-2xl font-bold text-indigo-600">{{ count($traceability['timeline']) }}</p>
                </div>
            </div>
        </div>
        <div class="px-4 py-5">
            @if(count($traceability['timeline']) > 0)
                <div class="relative">
                    <!-- Vertical Timeline Line -->
                    <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-gradient-to-b from-blue-200 via-indigo-200 to-red-200"></div>
                    
                    @foreach($traceability['timeline'] as $index => $event)
                        <div class="relative mb-8 pl-20">
                            <!-- Timeline Dot -->
                            <div class="absolute left-0 top-2 w-16 h-16 rounded-full border-4 border-white shadow-lg flex items-center justify-center
                                {{ $event['type'] === 'complaint' ? 'bg-red-500' : 
                                   ($event['type'] === 'storage_log' ? 'bg-yellow-500' : 
                                   ($event['type'] === 'cooking' ? 'bg-purple-500' : 
                                   ($event['type'] === 'order_served' ? 'bg-indigo-500' : 
                                   ($event['type'] === 'processing' ? 'bg-green-500' : 'bg-blue-500')))) }}">
                                @if($event['type'] === 'supplier_delivery')
                                    <span class="text-white text-xl">🏭</span>
                                @elseif($event['type'] === 'storage_log')
                                    <span class="text-white text-xl">🌡️</span>
                                @elseif($event['type'] === 'processing')
                                    <span class="text-white text-xl">🍢</span>
                                @elseif($event['type'] === 'cooking')
                                    <span class="text-white text-xl">🔥</span>
                                @elseif($event['type'] === 'order_served')
                                    <span class="text-white text-xl">📋</span>
                                @elseif($event['type'] === 'complaint')
                                    <span class="text-white text-xl">🚨</span>
                                @endif
                            </div>

                            <!-- Event Card -->
                            <div class="bg-white rounded-lg shadow-md border-l-4 p-5 hover:shadow-lg transition-shadow
                                {{ $event['type'] === 'complaint' ? 'border-red-500 bg-red-50' : 
                                   ($event['type'] === 'storage_log' ? 'border-yellow-500' : 
                                   ($event['type'] === 'cooking' ? 'border-purple-500' : 
                                   ($event['type'] === 'order_served' ? 'border-indigo-500' : 
                                   ($event['type'] === 'processing' ? 'border-green-500' : 'border-blue-500')))) }}">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <h3 class="text-base font-bold text-gray-900">{{ $event['title'] }}</h3>
                                            @if($event['type'] === 'complaint')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-800 animate-pulse">⚠️ CRITICAL</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-700 mb-2">{{ $event['description'] }}</p>
                                        <div class="flex items-center gap-4 text-xs text-gray-500">
                                            <span class="flex items-center">
                                                <span class="mr-1">📅</span>
                                                {{ $event['date']->format('F d, Y') }}
                                            </span>
                                            <span class="flex items-center">
                                                <span class="mr-1">🕐</span>
                                                {{ $event['date']->format('H:i') }}
                                            </span>
                                            @if($event['type'] === 'storage_log' && isset($event['data']->temperature_celsius))
                                                @if($event['data']->temperature_celsius > 4)
                                                    <span class="flex items-center text-red-600 font-semibold">
                                                        <span class="mr-1">⚠️</span>
                                                        Temp: {{ $event['data']->temperature_celsius }}°C (UNSAFE)
                                                    </span>
                                                @else
                                                    <span class="flex items-center text-green-600">
                                                        <span class="mr-1">✓</span>
                                                        Temp: {{ $event['data']->temperature_celsius }}°C
                                                    </span>
                                                @endif
                                            @endif
                                            @if($event['type'] === 'cooking' && isset($event['data']->cooking_temperature_celsius))
                                                @if($event['data']->cooking_temperature_celsius < 75)
                                                    <span class="flex items-center text-red-600 font-semibold">
                                                        <span class="mr-1">⚠️</span>
                                                        Cooked at {{ $event['data']->cooking_temperature_celsius }}°C (UNDERCOOKED)
                                                    </span>
                                                @else
                                                    <span class="flex items-center text-green-600">
                                                        <span class="mr-1">✓</span>
                                                        Cooked at {{ $event['data']->cooking_temperature_celsius }}°C
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        @if($event['type'] === 'supplier_delivery')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">🏭 Supplier</span>
                                        @elseif($event['type'] === 'storage_log')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">🌡️ Storage</span>
                                        @elseif($event['type'] === 'processing')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">🍢 Processing</span>
                                        @elseif($event['type'] === 'cooking')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">🔥 Cooking</span>
                                        @elseif($event['type'] === 'order_served')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 border border-indigo-200">📋 Order</span>
                                        @elseif($event['type'] === 'complaint')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200 animate-pulse">🚨 Incident</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500">No timeline data available.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Supplier Information -->
    @if(count($traceability['suppliers']) > 0)
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Source Supplier(s)</h2>
        </div>
        <div class="px-4 py-5">
            @foreach($traceability['suppliers'] as $supplier)
            <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                <h3 class="text-sm font-semibold text-gray-900">{{ $supplier->name }}</h3>
                <p class="mt-1 text-sm text-gray-600">License: {{ $supplier->license_number ?? 'N/A' }}</p>
                <p class="mt-1 text-sm text-gray-600">Contact: {{ $supplier->contact_person ?? 'N/A' }} - {{ $supplier->phone ?? 'N/A' }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Raw Meat Batch Information -->
    @if(count($traceability['raw_meat_batches']) > 0)
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Raw Meat Batch(es)</h2>
        </div>
        <div class="px-4 py-5">
            @foreach($traceability['raw_meat_batches'] as $batch)
            <div class="mb-4 p-4 bg-yellow-50 rounded-lg">
                <h3 class="text-sm font-semibold text-gray-900">Batch: {{ $batch->batch_number }}</h3>
                <p class="mt-1 text-sm text-gray-600">Production: {{ $batch->production_date->format('M d, Y') }}</p>
                <p class="mt-1 text-sm text-gray-600">Expiration: {{ $batch->expiration_date->format('M d, Y') }}</p>
                <p class="mt-1 text-sm text-gray-600">Quantity: {{ $batch->quantity_kg }} kg</p>
                <p class="mt-1 text-sm text-gray-600">Supplier: {{ $batch->supplier->name }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Processing Batch Information -->
    @if(count($traceability['processing_batches']) > 0)
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Kofta Processing Batch(es)</h2>
        </div>
        <div class="px-4 py-5">
            @foreach($traceability['processing_batches'] as $batch)
            <div class="mb-4 p-4 bg-green-50 rounded-lg">
                <h3 class="text-sm font-semibold text-gray-900">Batch: {{ $batch->batch_number }}</h3>
                <p class="mt-1 text-sm text-gray-600">Production: {{ $batch->production_date->format('M d, Y') }}</p>
                <p class="mt-1 text-sm text-gray-600">Quantity: {{ $batch->quantity_units }} units</p>
                <p class="mt-1 text-sm text-gray-600">From Raw Meat Batch: {{ $batch->rawMeatBatch->batch_number }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Order Information -->
    @if($traceability['order'])
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Order Information</h2>
        </div>
        <div class="px-4 py-5">
            <div class="p-4 bg-indigo-50 rounded-lg">
                <h3 class="text-sm font-semibold text-gray-900">Order: {{ $traceability['order']->order_number }}</h3>
                <p class="mt-1 text-sm text-gray-600">Customer: {{ $traceability['order']->customer_name ?? 'N/A' }}</p>
                <p class="mt-1 text-sm text-gray-600">Served: {{ $traceability['order']->served_at ? $traceability['order']->served_at->format('M d, Y H:i') : 'N/A' }}</p>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

