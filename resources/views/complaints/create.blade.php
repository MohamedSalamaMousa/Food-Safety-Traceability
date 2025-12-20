@extends('layouts.app')

@section('title', 'Report Complaint')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Report Food Poisoning Complaint</h1>
        <form action="{{ route('complaints.store') }}" method="POST" class="space-y-6 bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
            @csrf
            <div>
                <label for="order_id" class="block text-sm font-medium text-gray-700">Order *</label>
                <select name="order_id" id="order_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Select an order</option>
                    @foreach($orders as $order)
                        <option value="{{ $order->id }}" 
                                data-customer-name="{{ $order->customer_name ?? '' }}"
                                data-customer-phone="{{ $order->customer_phone ?? '' }}"
                                {{ old('order_id') == $order->id ? 'selected' : '' }}>
                            {{ $order->order_number }} - {{ $order->customer_name ?? 'N/A' }} 
                            @if($order->served_at)
                                (Served: {{ $order->served_at->format('M d, Y') }})
                            @else
                                (Status: {{ ucfirst($order->status) }})
                            @endif
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-sm text-gray-500">Select the order related to this complaint</p>
                @error('order_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="processing_batch_id" class="block text-sm font-medium text-gray-700">Processing Batch (Optional)</label>
                <select name="processing_batch_id" id="processing_batch_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">No specific batch (will be traced from order)</option>
                    @foreach($processingBatches as $batch)
                        <option value="{{ $batch->id }}" {{ old('processing_batch_id') == $batch->id ? 'selected' : '' }}>{{ $batch->batch_number }} ({{ $batch->quantity_units }} units)</option>
                    @endforeach
                </select>
                <p class="mt-1 text-sm text-gray-500">If you know the specific kofta batch, select it here. Otherwise, it will be traced from the order.</p>
                @error('processing_batch_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="customer_name" class="block text-sm font-medium text-gray-700">Customer Name *</label>
                <input type="text" name="customer_name" id="customer_name" required value="{{ old('customer_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('customer_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="customer_phone" class="block text-sm font-medium text-gray-700">Customer Phone *</label>
                <input type="text" name="customer_phone" id="customer_phone" required value="{{ old('customer_phone') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('customer_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="symptoms" class="block text-sm font-medium text-gray-700">Symptoms *</label>
                <textarea name="symptoms" id="symptoms" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('symptoms') }}</textarea>
                @error('symptoms')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="incident_description" class="block text-sm font-medium text-gray-700">Incident Description *</label>
                <textarea name="incident_description" id="incident_description" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('incident_description') }}</textarea>
                @error('incident_description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="incident_date" class="block text-sm font-medium text-gray-700">Incident Date & Time *</label>
                <input type="datetime-local" name="incident_date" id="incident_date" required value="{{ old('incident_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('incident_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="severity" class="block text-sm font-medium text-gray-700">Severity *</label>
                <select name="severity" id="severity" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="low" {{ old('severity') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('severity', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('severity') === 'high' ? 'selected' : '' }}>High</option>
                    <option value="critical" {{ old('severity') === 'critical' ? 'selected' : '' }}>Critical</option>
                </select>
                @error('severity')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                <select name="status" id="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="reported" {{ old('status', 'reported') === 'reported' ? 'selected' : '' }}>Reported</option>
                    <option value="investigating" {{ old('status') === 'investigating' ? 'selected' : '' }}>Investigating</option>
                    <option value="resolved" {{ old('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="investigation_notes" class="block text-sm font-medium text-gray-700">Investigation Notes (Optional)</label>
                <textarea name="investigation_notes" id="investigation_notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('investigation_notes') }}</textarea>
                @error('investigation_notes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="flex justify-end space-x-3">
                <a href="{{ route('complaints.index') }}" class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">Cancel</a>
                <button type="submit" class="rounded-md border border-transparent bg-red-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-red-700">Report Complaint</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Auto-populate customer info when order is selected
    document.getElementById('order_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const customerName = selectedOption.getAttribute('data-customer-name');
        const customerPhone = selectedOption.getAttribute('data-customer-phone');
        
        if (customerName) {
            document.getElementById('customer_name').value = customerName;
        }
        if (customerPhone) {
            document.getElementById('customer_phone').value = customerPhone;
        }
    });
</script>
@endsection

