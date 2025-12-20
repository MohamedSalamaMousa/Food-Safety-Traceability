@extends('layouts.app')

@section('title', 'Cooking Logs')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900">Cooking Logs</h1>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('cooking.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">Log Cooking</a>
        </div>
    </div>
    <div class="mt-8">
        <div class="overflow-hidden shadow-lg ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gradient-to-r from-purple-50 to-purple-100">
                    <tr>
                        <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Cooked At</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Processing Batch</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Quantity</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Temperature</th>
                        <th class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($cookingLogs as $log)
                    <tr>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $log->cooked_at->format('M d, Y H:i') }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $log->processingBatch->batch_number }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $log->quantity_cooked }} units</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $log->cooking_temperature_celsius }}°C</td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            <a href="{{ route('cooking.show', $log) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No cooking logs found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $cookingLogs->links() }}</div>
</div>
@endsection

