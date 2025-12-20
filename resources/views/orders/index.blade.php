@extends('layouts.app')

@section('title', 'Orders')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900">Orders</h1>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('orders.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">New Order</a>
        </div>
    </div>
    <div class="mt-8">
        <div class="overflow-hidden shadow-lg ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gradient-to-r from-blue-50 to-indigo-100">
                    <tr>
                        <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Order #</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Customer</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Served At</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                        <th class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($orders as $order)
                    <tr>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $order->order_number }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $order->customer_name ?? 'N/A' }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $order->served_at ? $order->served_at->format('M d, Y H:i') : 'N/A' }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'preparing' => 'bg-blue-100 text-blue-800',
                                    'ready' => 'bg-green-100 text-green-800',
                                    'served' => 'bg-indigo-100 text-indigo-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];
                                $color = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $color }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No orders found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $orders->links() }}</div>
</div>
@endsection

