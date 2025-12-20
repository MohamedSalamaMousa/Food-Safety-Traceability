@extends('layouts.app')

@section('title', 'Raw Meat Batches')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900">Raw Meat Batches</h1>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('raw-meat-batches.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">Add Batch</a>
        </div>
    </div>
    <div class="mt-8">
        <div class="overflow-hidden shadow-lg ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gradient-to-r from-yellow-50 to-yellow-100">
                    <tr>
                        <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Batch #</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Supplier</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Production Date</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Quantity</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                        <th class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($batches as $batch)
                    <tr>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $batch->batch_number }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $batch->supplier->name }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $batch->production_date->format('M d, Y') }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $batch->quantity_kg }} kg</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                            @php
                                $statusColors = [
                                    'received' => 'bg-blue-100 text-blue-800',
                                    'in_storage' => 'bg-green-100 text-green-800',
                                    'processing' => 'bg-yellow-100 text-yellow-800',
                                    'expired' => 'bg-red-100 text-red-800',
                                    'used' => 'bg-gray-100 text-gray-800',
                                ];
                                $color = $statusColors[$batch->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $color }}">
                                {{ ucfirst(str_replace('_', ' ', $batch->status)) }}
                            </span>
                            @if($batch->expiration_date < now() && $batch->status !== 'expired')
                                <span class="ml-2 text-red-600" title="Expired">⚠️</span>
                            @endif
                        </td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            <a href="{{ route('raw-meat-batches.show', $batch) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No batches found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $batches->links() }}</div>
</div>
@endsection

