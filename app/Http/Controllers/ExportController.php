<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    /**
     * Export complaint traceability report as CSV
     */
    public function exportComplaintTraceability($id)
    {
        $complaint = Complaint::with([
            'order.orderItems.cookingLog.processingBatch.rawMeatBatch.supplier',
            'processingBatch.rawMeatBatch.supplier'
        ])->findOrFail($id);

        $filename = 'complaint-traceability-' . $complaint->complaint_number . '-' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($complaint) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['Food Safety Traceability Report']);
            fputcsv($file, ['Complaint Number', $complaint->complaint_number]);
            fputcsv($file, ['Customer', $complaint->customer_name]);
            fputcsv($file, ['Incident Date', $complaint->incident_date->format('Y-m-d H:i:s')]);
            fputcsv($file, ['Severity', ucfirst($complaint->severity)]);
            fputcsv($file, ['Status', ucfirst($complaint->status)]);
            fputcsv($file, []);
            
            // Order Information
            if ($complaint->order) {
                fputcsv($file, ['ORDER INFORMATION']);
                fputcsv($file, ['Order Number', $complaint->order->order_number]);
                fputcsv($file, ['Served At', $complaint->order->served_at ? $complaint->order->served_at->format('Y-m-d H:i:s') : 'N/A']);
                fputcsv($file, []);
            }
            
            // Traceability Chain
            fputcsv($file, ['TRACEABILITY CHAIN']);
            fputcsv($file, ['Step', 'Type', 'Identifier', 'Date', 'Details']);
            
            if ($complaint->order) {
                foreach ($complaint->order->orderItems as $item) {
                    if ($item->cookingLog && $item->cookingLog->processingBatch) {
                        $pb = $item->cookingLog->processingBatch;
                        $rmb = $pb->rawMeatBatch;
                        $supplier = $rmb->supplier;
                        
                        fputcsv($file, ['1', 'Supplier', $supplier->name, $rmb->production_date->format('Y-m-d'), 'License: ' . $supplier->license_number]);
                        fputcsv($file, ['2', 'Raw Meat Batch', $rmb->batch_number, $rmb->production_date->format('Y-m-d'), $rmb->quantity_kg . ' kg']);
                        fputcsv($file, ['3', 'Processing Batch', $pb->batch_number, $pb->production_date->format('Y-m-d'), $pb->quantity_units . ' units']);
                        fputcsv($file, ['4', 'Cooking', 'Cooked', $item->cookingLog->cooked_at->format('Y-m-d H:i:s'), $item->cookingLog->cooking_temperature_celsius . '°C']);
                        fputcsv($file, ['5', 'Order', $complaint->order->order_number, $complaint->order->served_at->format('Y-m-d H:i:s'), 'Served to customer']);
                    }
                }
            }
            
            fputcsv($file, []);
            fputcsv($file, ['Symptoms', $complaint->symptoms]);
            fputcsv($file, ['Description', $complaint->incident_description]);
            
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
