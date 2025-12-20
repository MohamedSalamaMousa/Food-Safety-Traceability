<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\RawMeatBatch;
use App\Models\StorageLog;
use App\Models\ProcessingBatch;
use App\Models\CookingLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Complaint;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TraceabilityTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds with realistic food safety traceability scenario
     */
    public function run(): void
    {
        $this->command->info('Creating realistic traceability test data...');

        // 1. Create Suppliers
        $supplier1 = Supplier::create([
            'name' => 'Fresh Meat Co.',
            'contact_person' => 'John Smith',
            'phone' => '+1-555-0101',
            'email' => 'john@freshmeatco.com',
            'address' => '123 Farm Road, Agricultural District',
            'license_number' => 'FM-2024-001',
            'status' => 'active',
        ]);

        $supplier2 = Supplier::create([
            'name' => 'Premium Meats Ltd.',
            'contact_person' => 'Sarah Johnson',
            'phone' => '+1-555-0102',
            'email' => 'sarah@premiummeats.com',
            'address' => '456 Quality Street, Food Zone',
            'license_number' => 'PM-2024-002',
            'status' => 'active',
        ]);

        $this->command->info('✓ Created 2 suppliers');

        // 2. Create Raw Meat Batches (from Supplier 1 - this will be the problematic batch)
        $rawMeatBatch1 = RawMeatBatch::create([
            'batch_number' => 'RMB-2024-001',
            'supplier_id' => $supplier1->id,
            'production_date' => Carbon::now()->subDays(10),
            'expiration_date' => Carbon::now()->subDays(3),
            'quantity_kg' => 50.5,
            'status' => 'used',
            'notes' => 'Initial delivery from Fresh Meat Co.',
        ]);

        $rawMeatBatch2 = RawMeatBatch::create([
            'batch_number' => 'RMB-2024-002',
            'supplier_id' => $supplier2->id,
            'production_date' => Carbon::now()->subDays(8),
            'expiration_date' => Carbon::now()->addDays(2),
            'quantity_kg' => 75.0,
            'status' => 'in_storage',
            'notes' => 'Quality batch from Premium Meats',
        ]);

        $this->command->info('✓ Created 2 raw meat batches');

        // 3. Create Storage Logs for Raw Meat Batch 1 (the problematic one)
        StorageLog::create([
            'raw_meat_batch_id' => $rawMeatBatch1->id,
            'temperature_celsius' => 2.5,
            'humidity_percentage' => 65.0,
            'notes' => 'Initial storage - temperature OK',
            'logged_at' => Carbon::now()->subDays(10)->addHours(2),
        ]);

        StorageLog::create([
            'raw_meat_batch_id' => $rawMeatBatch1->id,
            'temperature_celsius' => 3.2,
            'humidity_percentage' => 68.0,
            'notes' => 'Daily check - slight increase',
            'logged_at' => Carbon::now()->subDays(9)->addHours(10),
        ]);

        StorageLog::create([
            'raw_meat_batch_id' => $rawMeatBatch1->id,
            'temperature_celsius' => 5.8, // ⚠️ RISK: Temperature too high!
            'humidity_percentage' => 72.0,
            'notes' => '⚠️ WARNING: Temperature exceeded safe limit (4°C)',
            'logged_at' => Carbon::now()->subDays(8)->addHours(14),
        ]);

        StorageLog::create([
            'raw_meat_batch_id' => $rawMeatBatch1->id,
            'temperature_celsius' => 4.1,
            'humidity_percentage' => 70.0,
            'notes' => 'Temperature corrected',
            'logged_at' => Carbon::now()->subDays(7)->addHours(8),
        ]);

        $this->command->info('✓ Created storage logs (including temperature risk)');

        // 4. Create Processing Batches (Kofta batches)
        $processingBatch1 = ProcessingBatch::create([
            'batch_number' => 'KFT-2024-001',
            'raw_meat_batch_id' => $rawMeatBatch1->id, // From the problematic raw meat
            'production_date' => Carbon::now()->subDays(6),
            'expiration_date' => Carbon::now()->subDays(1),
            'quantity_units' => 200, // 200 kofta sticks
            'status' => 'sold',
            'notes' => 'Kofta batch made from RMB-2024-001',
        ]);

        $processingBatch2 = ProcessingBatch::create([
            'batch_number' => 'KFT-2024-002',
            'raw_meat_batch_id' => $rawMeatBatch2->id,
            'production_date' => Carbon::now()->subDays(4),
            'expiration_date' => Carbon::now()->addDays(3),
            'quantity_units' => 300,
            'status' => 'ready',
            'notes' => 'Kofta batch from quality supplier',
        ]);

        $this->command->info('✓ Created 2 processing batches (kofta)');

        // 5. Create Cooking Logs
        $cookingLog1 = CookingLog::create([
            'processing_batch_id' => $processingBatch1->id,
            'order_id' => null, // Cooked for stock first
            'quantity_cooked' => 50,
            'cooking_temperature_celsius' => 75.0,
            'cooking_duration_minutes' => 15,
            'cooked_at' => Carbon::now()->subDays(5)->addHours(10),
            'notes' => 'First batch cooked',
        ]);

        $cookingLog2 = CookingLog::create([
            'processing_batch_id' => $processingBatch1->id,
            'order_id' => null,
            'quantity_cooked' => 30,
            'cooking_temperature_celsius' => 72.0, // ⚠️ RISK: Slightly undercooked
            'cooking_duration_minutes' => 12,
            'cooked_at' => Carbon::now()->subDays(4)->addHours(14),
            'notes' => 'Second batch - rushed cooking',
        ]);

        $cookingLog3 = CookingLog::create([
            'processing_batch_id' => $processingBatch1->id,
            'order_id' => null,
            'quantity_cooked' => 40,
            'cooking_temperature_celsius' => 78.0,
            'cooking_duration_minutes' => 18,
            'cooked_at' => Carbon::now()->subDays(3)->addHours(11),
            'notes' => 'Third batch',
        ]);

        $cookingLog4 = CookingLog::create([
            'processing_batch_id' => $processingBatch2->id,
            'order_id' => null,
            'quantity_cooked' => 100,
            'cooking_temperature_celsius' => 80.0,
            'cooking_duration_minutes' => 20,
            'cooked_at' => Carbon::now()->subDays(2)->addHours(9),
            'notes' => 'Quality batch cooking',
        ]);

        $this->command->info('✓ Created 4 cooking logs');

        // 6. Create Orders
        $order1 = Order::create([
            'order_number' => 'ORD-2024-001',
            'customer_name' => 'Ahmed Hassan',
            'customer_phone' => '+1-555-1001',
            'status' => 'served',
            'served_at' => Carbon::now()->subDays(3)->addHours(12),
            'notes' => 'Customer order - table 5',
        ]);

        $order2 = Order::create([
            'order_number' => 'ORD-2024-002',
            'customer_name' => 'Maria Garcia',
            'customer_phone' => '+1-555-1002',
            'status' => 'served',
            'served_at' => Carbon::now()->subDays(2)->addHours(13),
            'notes' => 'Customer order - table 8',
        ]);

        $order3 = Order::create([
            'order_number' => 'ORD-2024-003',
            'customer_name' => 'David Chen',
            'customer_phone' => '+1-555-1003',
            'status' => 'served',
            'served_at' => Carbon::now()->subDays(2)->addHours(14),
            'notes' => 'Customer order - takeout',
        ]);

        $order4 = Order::create([
            'order_number' => 'ORD-2024-004',
            'customer_name' => 'Emma Wilson',
            'customer_phone' => '+1-555-1004',
            'status' => 'served',
            'served_at' => Carbon::now()->subDays(1)->addHours(15),
            'notes' => 'Customer order - table 12',
        ]);

        $this->command->info('✓ Created 4 orders');

        // 7. Create Order Items (link orders to cooking logs)
        OrderItem::create([
            'order_id' => $order1->id,
            'cooking_log_id' => $cookingLog1->id,
            'quantity' => 5,
            'unit_price' => 12.50,
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'cooking_log_id' => $cookingLog2->id, // ⚠️ This is the problematic cooking log
            'quantity' => 3,
            'unit_price' => 12.50,
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'cooking_log_id' => $cookingLog2->id, // ⚠️ Same problematic batch
            'quantity' => 4,
            'unit_price' => 12.50,
        ]);

        OrderItem::create([
            'order_id' => $order4->id,
            'cooking_log_id' => $cookingLog4->id,
            'quantity' => 6,
            'unit_price' => 12.50,
        ]);

        // Update cooking logs to link to orders
        $cookingLog1->update(['order_id' => $order1->id]);
        $cookingLog2->update(['order_id' => $order2->id]); // This will be the problematic one
        $cookingLog4->update(['order_id' => $order4->id]);

        $this->command->info('✓ Created order items');

        // 8. Create Food Poisoning Complaint (THE INCIDENT)
        $complaint = Complaint::create([
            'order_id' => $order2->id, // Order from the problematic cooking log
            'processing_batch_id' => $processingBatch1->id, // The problematic processing batch
            'complaint_number' => 'COMP-' . strtoupper(Str::random(8)),
            'customer_name' => 'Maria Garcia',
            'customer_phone' => '+1-555-1002',
            'symptoms' => 'Nausea, vomiting, abdominal cramps, diarrhea, fever',
            'incident_description' => 'Customer reported severe food poisoning symptoms 4 hours after consuming kofta. Symptoms started with nausea and progressed to vomiting and diarrhea. Customer visited emergency room.',
            'incident_date' => Carbon::now()->subDays(2)->addHours(17), // 4 hours after being served
            'severity' => 'high',
            'status' => 'investigating',
            'investigation_notes' => 'Initial investigation: Suspected contamination from raw meat batch RMB-2024-001. Temperature logs show unsafe storage temperature (5.8°C) on day 2. Cooking log shows undercooked batch (72°C, 12 min). Full traceability chain identified.',
        ]);

        $this->command->info('✓ Created food poisoning complaint');

        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════════════');
        $this->command->info('  TEST DATA SCENARIO SUMMARY');
        $this->command->info('═══════════════════════════════════════════════════════════');
        $this->command->info('');
        $this->command->info('📋 TRACEABILITY CHAIN:');
        $this->command->info('   Complaint: ' . $complaint->complaint_number);
        $this->command->info('   ↓ Order: ' . $order2->order_number . ' (Maria Garcia)');
        $this->command->info('   ↓ Cooking Log: Cooked at 72°C (UNDERCOOKED ⚠️)');
        $this->command->info('   ↓ Processing Batch: ' . $processingBatch1->batch_number);
        $this->command->info('   ↓ Raw Meat Batch: ' . $rawMeatBatch1->batch_number);
        $this->command->info('   ↓ Supplier: ' . $supplier1->name);
        $this->command->info('');
        $this->command->info('⚠️  RISK POINTS IDENTIFIED:');
        $this->command->info('   1. Storage temperature exceeded 4°C (reached 5.8°C)');
        $this->command->info('   2. Cooking temperature too low (72°C, should be 75°C+)');
        $this->command->info('   3. Cooking duration too short (12 min, should be 15+ min)');
        $this->command->info('');
        $this->command->info('✅ Test data created successfully!');
        $this->command->info('   View the complaint traceability at: /complaints/' . $complaint->id);
        $this->command->info('');
    }
}
