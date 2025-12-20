@extends('layouts.app')

@section('title', 'Log Cooking')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Log Kofta Cooking</h1>
        <form action="{{ route('cooking.store') }}" method="POST" class="space-y-6 bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
            @csrf
            <div>
                <label for="processing_batch_id" class="block text-sm font-medium text-gray-700">Processing Batch *</label>
                <select name="processing_batch_id" id="processing_batch_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Select processing batch</option>
                    @foreach($processingBatches as $batch)
                        <option value="{{ $batch->id }}" {{ old('processing_batch_id') == $batch->id ? 'selected' : '' }}>{{ $batch->batch_number }} ({{ $batch->quantity_units }} units)</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="quantity_cooked" class="block text-sm font-medium text-gray-700">Quantity Cooked *</label>
                <input type="number" name="quantity_cooked" id="quantity_cooked" required value="{{ old('quantity_cooked') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="cooking_temperature_celsius" class="block text-sm font-medium text-gray-700">Cooking Temperature (°C) *</label>
                <input type="number" step="0.01" name="cooking_temperature_celsius" id="cooking_temperature_celsius" required value="{{ old('cooking_temperature_celsius') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="cooking_duration_minutes" class="block text-sm font-medium text-gray-700">Cooking Duration (minutes) *</label>
                <input type="number" name="cooking_duration_minutes" id="cooking_duration_minutes" required value="{{ old('cooking_duration_minutes') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="order_id" class="block text-sm font-medium text-gray-700">Order (Optional)</label>
                <select name="order_id" id="order_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">No order (cook for stock)</option>
                    @foreach($orders as $order)
                        <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>{{ $order->order_number }} - {{ $order->customer_name ?? 'N/A' }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="cooked_at" class="block text-sm font-medium text-gray-700">Cooked At (Optional)</label>
                <input type="datetime-local" name="cooked_at" id="cooked_at" value="{{ old('cooked_at') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <p class="mt-1 text-sm text-gray-500">Leave empty to use current time</p>
            </div>
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('notes') }}</textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <a href="{{ route('cooking.index') }}" class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">Cancel</a>
                <button type="submit" class="rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">Log Cooking</button>
            </div>
        </form>
    </div>
</div>
@endsection

