@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Food Safety Dashboard</h1>
        <p class="mt-2 text-sm text-gray-600">Real-time overview of your traceability system</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <span class="text-white text-sm">🏭</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Suppliers</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['suppliers'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <span class="text-white text-sm">🥩</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Raw Meat Batches</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['raw_meat_batches'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <span class="text-white text-sm">🍢</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Processing Batches</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['processing_batches'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                            <span class="text-white text-sm">📋</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Orders Today</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['orders_today'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                            <span class="text-white text-sm">⚠️</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Complaints</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['complaints_active'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                            <span class="text-white text-sm">🚨</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Critical Issues</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['complaints_critical'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Risk Alerts -->
    @if(count($riskAlerts) > 0)
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">⚠️ Risk Alerts</h2>
        <div class="space-y-3">
            @foreach($riskAlerts as $alert)
            <div class="bg-{{ $alert['type'] === 'danger' ? 'red' : 'yellow' }}-50 border-l-4 border-{{ $alert['type'] === 'danger' ? 'red' : 'yellow' }}-400 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <span class="text-2xl">{{ $alert['icon'] }}</span>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-{{ $alert['type'] === 'danger' ? 'red' : 'yellow' }}-800">
                            {{ $alert['title'] }}
                        </p>
                        <p class="mt-1 text-sm text-{{ $alert['type'] === 'danger' ? 'red' : 'yellow' }}-700">
                            {{ $alert['message'] }}
                        </p>
                        @if(isset($alert['link']))
                        <p class="mt-2">
                            <a href="{{ $alert['link'] }}" class="text-sm font-medium text-{{ $alert['type'] === 'danger' ? 'red' : 'yellow' }}-800 hover:text-{{ $alert['type'] === 'danger' ? 'red' : 'yellow' }}-900">
                                View Details →
                            </a>
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <!-- Recent Complaints -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Recent Complaints</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                @if($recentComplaints->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($recentComplaints as $complaint)
                    <li class="py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('complaints.show', $complaint) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                    {{ $complaint->complaint_number }}
                                </a>
                                <p class="mt-1 text-sm text-gray-500">{{ $complaint->customer_name }}</p>
                                <p class="mt-1 text-xs text-gray-400">{{ $complaint->incident_date->format('M d, Y H:i') }}</p>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $complaint->severity === 'critical' ? 'bg-red-100 text-red-800' : 
                                       ($complaint->severity === 'high' ? 'bg-orange-100 text-orange-800' : 
                                       ($complaint->severity === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800')) }}">
                                    {{ ucfirst($complaint->severity) }}
                                </span>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
                <div class="mt-4">
                    <a href="{{ route('complaints.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">View all complaints →</a>
                </div>
                @else
                <p class="text-sm text-gray-500">No recent complaints</p>
                @endif
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Recent Orders</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                @if($recentOrders->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($recentOrders as $order)
                    <li class="py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('orders.show', $order) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                    {{ $order->order_number }}
                                </a>
                                <p class="mt-1 text-sm text-gray-500">{{ $order->customer_name ?? 'N/A' }}</p>
                                <p class="mt-1 text-xs text-gray-400">
                                    @if($order->served_at)
                                        Served: {{ $order->served_at->format('M d, Y H:i') }}
                                    @else
                                        Status: {{ ucfirst($order->status) }}
                                    @endif
                                </p>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
                <div class="mt-4">
                    <a href="{{ route('orders.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">View all orders →</a>
                </div>
                @else
                <p class="text-sm text-gray-500">No recent orders</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <a href="{{ route('suppliers.create') }}" class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                    <div class="flex-shrink-0">
                        <span class="text-2xl">➕</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="absolute inset-0" aria-hidden="true"></span>
                        <p class="text-sm font-medium text-gray-900">Add Supplier</p>
                    </div>
                </a>
                <a href="{{ route('raw-meat-batches.create') }}" class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                    <div class="flex-shrink-0">
                        <span class="text-2xl">🥩</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="absolute inset-0" aria-hidden="true"></span>
                        <p class="text-sm font-medium text-gray-900">Receive Raw Meat</p>
                    </div>
                </a>
                <a href="{{ route('processing-batches.create') }}" class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                    <div class="flex-shrink-0">
                        <span class="text-2xl">🍢</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="absolute inset-0" aria-hidden="true"></span>
                        <p class="text-sm font-medium text-gray-900">Create Kofta Batch</p>
                    </div>
                </a>
                <a href="{{ route('complaints.create') }}" class="relative rounded-lg border border-red-300 bg-red-50 px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-red-400 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-red-500">
                    <div class="flex-shrink-0">
                        <span class="text-2xl">🚨</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="absolute inset-0" aria-hidden="true"></span>
                        <p class="text-sm font-medium text-red-900">Report Complaint</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection


