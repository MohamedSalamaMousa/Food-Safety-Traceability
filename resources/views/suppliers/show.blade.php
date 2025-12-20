@extends('layouts.app')

@section('title', 'Supplier Details')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="mb-4">
        <a href="{{ route('suppliers.index') }}" class="text-indigo-600 hover:text-indigo-900">← Back to Suppliers</a>
    </div>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h1 class="text-2xl font-semibold text-gray-900">{{ $supplier->name }}</h1>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Supplier information and traceability</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Contact Person</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $supplier->contact_person ?? 'N/A' }}</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $supplier->phone ?? 'N/A' }}</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $supplier->email ?? 'N/A' }}</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $supplier->status === 'active' ? 'bg-green-100 text-green-800' : ($supplier->status === 'suspended' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($supplier->status) }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
    <div class="mt-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Raw Meat Batches from this Supplier</h2>
        @if($supplier->rawMeatBatches->count() > 0)
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @foreach($supplier->rawMeatBatches as $batch)
                    <li>
                        <a href="{{ route('raw-meat-batches.show', $batch) }}" class="block hover:bg-gray-50">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-indigo-600 truncate">{{ $batch->batch_number }}</p>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $batch->status === 'used' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($batch->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p class="flex items-center text-sm text-gray-500">Production: {{ $batch->production_date->format('M d, Y') }}</p>
                                        <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">Quantity: {{ $batch->quantity_kg }} kg</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        @else
            <p class="text-sm text-gray-500">No raw meat batches from this supplier yet.</p>
        @endif
    </div>
</div>
@endsection


