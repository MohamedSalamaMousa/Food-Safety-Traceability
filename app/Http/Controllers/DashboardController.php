<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\RawMeatBatch;
use App\Models\ProcessingBatch;
use App\Models\CookingLog;
use App\Models\Order;
use App\Models\Complaint;
use App\Models\StorageLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $stats = [
            'suppliers' => Supplier::where('status', 'active')->count(),
            'raw_meat_batches' => RawMeatBatch::count(),
            'processing_batches' => ProcessingBatch::count(),
            'orders_today' => Order::whereDate('created_at', today())->count(),
            'complaints_active' => Complaint::whereIn('status', ['reported', 'investigating'])->count(),
            'complaints_critical' => Complaint::where('severity', 'critical')->count(),
        ];

        // Risk Alerts
        $riskAlerts = [];
        
        // Check for expired batches
        $expiredBatches = ProcessingBatch::where('expiration_date', '<', now())
            ->whereNotIn('status', ['expired', 'wasted'])
            ->count();
        if ($expiredBatches > 0) {
            $riskAlerts[] = [
                'type' => 'warning',
                'icon' => '⚠️',
                'title' => 'Expired Batches',
                'message' => "{$expiredBatches} processing batch(es) have expired and need attention.",
                'link' => route('processing-batches.index'),
            ];
        }

        // Check for unsafe storage temperatures
        $unsafeTemps = StorageLog::where('temperature_celsius', '>', 4)
            ->where('logged_at', '>=', now()->subDays(7))
            ->count();
        if ($unsafeTemps > 0) {
            $riskAlerts[] = [
                'type' => 'danger',
                'icon' => '🔥',
                'title' => 'Unsafe Storage Temperatures',
                'message' => "{$unsafeTemps} storage log(s) show temperatures above 4°C in the last 7 days.",
                'link' => route('raw-meat-batches.index'),
            ];
        }

        // Check for undercooked items
        $undercooked = CookingLog::where('cooking_temperature_celsius', '<', 75)
            ->where('cooked_at', '>=', now()->subDays(7))
            ->count();
        if ($undercooked > 0) {
            $riskAlerts[] = [
                'type' => 'danger',
                'icon' => '🌡️',
                'title' => 'Undercooked Items',
                'message' => "{$undercooked} cooking log(s) show temperatures below 75°C in the last 7 days.",
                'link' => route('cooking.index'),
            ];
        }

        // Recent complaints
        $recentComplaints = Complaint::with(['order', 'processingBatch'])
            ->latest('incident_date')
            ->limit(5)
            ->get();

        // Recent orders
        $recentOrders = Order::with('orderItems.cookingLog.processingBatch')
            ->latest('served_at')
            ->limit(5)
            ->get();

        // Batch status distribution
        $batchStatuses = [
            'raw_meat' => RawMeatBatch::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status'),
            'processing' => ProcessingBatch::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status'),
        ];

        return view('dashboard', compact('stats', 'riskAlerts', 'recentComplaints', 'recentOrders', 'batchStatuses'));
    }
}
