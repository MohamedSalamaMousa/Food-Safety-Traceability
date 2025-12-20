@extends('layouts.app')

@section('title', 'Create Raw Meat Batch')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Create Raw Meat Batch</h1>
        <form action="{{ route('raw-meat-batches.store') }}" method="POST" class="space-y-6 bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
            @csrf
            <div>
                <label for="batch_number" class="block text-sm font-medium text-gray-700">Batch Number *</label>
                <input type="text" name="batch_number" id="batch_number" required value="{{ old('batch_number') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('batch_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier *</label>
                <select name="supplier_id" id="supplier_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Select supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="production_date" class="block text-sm font-medium text-gray-700">Production Date *</label>
                <input type="date" name="production_date" id="production_date" required value="{{ old('production_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="expiration_date" class="block text-sm font-medium text-gray-700">Expiration Date *</label>
                <input type="date" name="expiration_date" id="expiration_date" required value="{{ old('expiration_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="quantity_kg" class="block text-sm font-medium text-gray-700">Quantity (kg) *</label>
                <input type="number" step="0.01" name="quantity_kg" id="quantity_kg" required value="{{ old('quantity_kg') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                <select name="status" id="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="received" {{ old('status') === 'received' ? 'selected' : '' }}>Received</option>
                    <option value="in_storage" {{ old('status') === 'in_storage' ? 'selected' : '' }}>In Storage</option>
                </select>
            </div>
            <div class="flex justify-end space-x-3">
                <a href="{{ route('raw-meat-batches.index') }}" class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">Cancel</a>
                <button type="submit" class="rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">Create</button>
            </div>
        </form>
    </div>
</div>
@endsection


