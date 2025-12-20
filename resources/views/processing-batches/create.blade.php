@extends('layouts.app')

@section('title', 'Create Processing Batch')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Create Kofta Processing Batch</h1>
        <form action="{{ route('processing-batches.store') }}" method="POST" class="space-y-6 bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
            @csrf
            <div>
                <label for="batch_number" class="block text-sm font-medium text-gray-700">Batch Number *</label>
                <input type="text" name="batch_number" id="batch_number" required value="{{ old('batch_number') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('batch_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="raw_meat_batch_id" class="block text-sm font-medium text-gray-700">Raw Meat Batch *</label>
                <select name="raw_meat_batch_id" id="raw_meat_batch_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Select raw meat batch</option>
                    @foreach($rawMeatBatches as $batch)
                        <option value="{{ $batch->id }}" {{ old('raw_meat_batch_id') == $batch->id ? 'selected' : '' }}>{{ $batch->batch_number }} ({{ $batch->quantity_kg }} kg)</option>
                    @endforeach
                </select>
                @error('raw_meat_batch_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="production_date" class="block text-sm font-medium text-gray-700">Production Date *</label>
                <input type="date" name="production_date" id="production_date" required value="{{ old('production_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('production_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="expiration_date" class="block text-sm font-medium text-gray-700">Expiration Date *</label>
                <input type="date" name="expiration_date" id="expiration_date" required value="{{ old('expiration_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('expiration_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="quantity_units" class="block text-sm font-medium text-gray-700">Quantity (units) *</label>
                <input type="number" name="quantity_units" id="quantity_units" required value="{{ old('quantity_units') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('quantity_units')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                <select name="status" id="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="processing" {{ old('status', 'processing') === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="ready" {{ old('status') === 'ready' ? 'selected' : '' }}>Ready</option>
                </select>
                @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('notes') }}</textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <a href="{{ route('processing-batches.index') }}" class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">Cancel</a>
                <button type="submit" class="rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">Create</button>
            </div>
        </form>
    </div>
</div>
@endsection

