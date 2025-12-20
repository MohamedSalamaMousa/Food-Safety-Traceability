@extends('layouts.app')

@section('title', 'Create Order')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Create Order</h1>
        <form action="{{ route('orders.store') }}" method="POST" class="space-y-6 bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
            @csrf
            <div>
                <label for="order_number" class="block text-sm font-medium text-gray-700">Order Number *</label>
                <input type="text" name="order_number" id="order_number" required value="{{ old('order_number') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('order_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="customer_name" class="block text-sm font-medium text-gray-700">Customer Name</label>
                <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('customer_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="customer_phone" class="block text-sm font-medium text-gray-700">Customer Phone</label>
                <input type="text" name="customer_phone" id="customer_phone" value="{{ old('customer_phone') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('customer_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                <select name="status" id="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="pending" {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="preparing" {{ old('status') === 'preparing' ? 'selected' : '' }}>Preparing</option>
                    <option value="ready" {{ old('status') === 'ready' ? 'selected' : '' }}>Ready</option>
                </select>
                @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('notes') }}</textarea>
                @error('notes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div id="items-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">Order Items *</label>
                <div class="item-row mb-4 p-4 border rounded">
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Cooking Log *</label>
                        <select name="items[0][cooking_log_id]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Select cooking log</option>
                            @foreach($cookingLogs as $log)
                                <option value="{{ $log->id }}" {{ old('items.0.cooking_log_id') == $log->id ? 'selected' : '' }}>Batch: {{ $log->processingBatch->batch_number }} - Cooked: {{ $log->cooked_at->format('M d, Y') }}</option>
                            @endforeach
                        </select>
                        @error('items.0.cooking_log_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Quantity *</label>
                        <input type="number" name="items[0][quantity]" required min="1" value="{{ old('items.0.quantity') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('items.0.quantity')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Unit Price (Optional)</label>
                        <input type="number" step="0.01" name="items[0][unit_price]" min="0" value="{{ old('items.0.unit_price') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
            </div>
            @error('items')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            @if($errors->has('items.*'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach($errors->get('items.*') as $error)
                            <li>{{ $error[0] }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="flex justify-end space-x-3">
                <a href="{{ route('orders.index') }}" class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">Cancel</a>
                <button type="submit" class="rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">Create Order</button>
            </div>
        </form>
    </div>
</div>
@endsection

