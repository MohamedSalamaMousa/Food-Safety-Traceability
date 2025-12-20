# 🔍 Food Safety Traceability System

A comprehensive Laravel-based food safety traceability system designed for restaurants to track food products from supplier to customer, with full lifecycle monitoring and incident investigation capabilities.

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Business Scenario](#business-scenario)
- [Technology Stack](#technology-stack)
- [Installation](#installation)
- [Database Setup](#database-setup)
- [Usage Guide](#usage-guide)
- [Traceability Flow](#traceability-flow)
- [API Documentation](#api-documentation)
- [Screenshots](#screenshots)

## 🎯 Overview

This system enables complete traceability of food products through the entire supply chain. When a food poisoning incident occurs, you can trace back from the customer complaint to the original supplier, identifying every step in the process including storage conditions, processing, cooking, and serving.

### Key Capabilities

- **Full Lifecycle Tracking**: Track products from supplier delivery to customer consumption
- **Temperature Monitoring**: Log and monitor storage temperatures with risk alerts
- **Incident Investigation**: Complete trace-back from complaint to source
- **Risk Management**: Automatic detection of unsafe conditions
- **Compliance Ready**: Maintain detailed records for food safety audits

## ✨ Features

### Core Functionality

1. **Supplier Management**
   - Register and manage meat suppliers
   - Track supplier licenses and contact information
   - Monitor supplier status (active, inactive, suspended)

2. **Raw Meat Batch Tracking**
   - Record incoming raw meat batches with batch numbers
   - Track production and expiration dates
   - Monitor storage conditions with temperature logs
   - Link batches to suppliers

3. **Processing Batch Management**
   - Create kofta processing batches from raw meat
   - Track batch quantities and production dates
   - Monitor batch status through lifecycle

4. **Cooking Logs**
   - Record cooking activities with temperature and duration
   - Link cooking to processing batches and orders
   - Monitor cooking temperatures for safety compliance

5. **Order Management**
   - Create customer orders
   - Link orders to cooking logs
   - Track order status and serving times

6. **Complaint Management & Traceability** ⭐
   - Register food poisoning complaints
   - **Complete trace-back** from complaint to supplier
   - Visual timeline of entire lifecycle
   - Export traceability reports

### Advanced Features

- **Dashboard**: Real-time overview with statistics and risk alerts
- **Search & Filters**: Quick search across all entities
- **Risk Alerts**: Automatic detection of:
  - Expired batches
  - Unsafe storage temperatures (>4°C)
  - Undercooked items (<75°C)
- **Export Functionality**: CSV export of traceability reports
- **Visual Timeline**: Beautiful timeline visualization of product lifecycle

## 🏢 Business Scenario

### The Problem

A restaurant receives raw meat from suppliers, processes it into kofta, cooks it, and serves it to customers. When a food poisoning incident occurs, management needs to:

1. Identify which customer order was affected
2. Trace back to the specific kofta batch
3. Identify the raw meat batch used
4. Find the supplier who provided the meat
5. Review all storage and cooking conditions

### The Solution

This system maintains complete records at every step, allowing instant traceability from any complaint back to the source supplier, including all intermediate steps and risk points.

## 🛠 Technology Stack

- **Framework**: Laravel 12.x
- **Database**: MySQL
- **Frontend**: Blade Templates with Tailwind CSS
- **Architecture**: MVC (Model-View-Controller)
- **PHP Version**: 8.2+

## 📦 Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL 5.7+ or MariaDB 10.3+
- Node.js and NPM (optional, for asset compilation)

### Step 1: Clone the Repository

```bash
git clone <repository-url>
cd tracing
```

### Step 2: Install Dependencies

```bash
composer install
```

### Step 3: Environment Configuration

Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Configure your database in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tracing
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 4: Run Migrations

```bash
php artisan migrate
```

### Step 5: Seed Test Data (Optional)

To populate the database with realistic test data:

```bash
php artisan db:seed --class=TraceabilityTestDataSeeder
```

This will create:
- 2 suppliers
- 2 raw meat batches
- Storage logs with temperature data
- 2 processing batches (kofta)
- 4 cooking logs
- 4 orders
- 1 food poisoning complaint with full traceability

### Step 6: Start the Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## 📊 Database Setup

The system uses the following database tables:

### Core Tables

- **suppliers**: Meat supplier information
- **raw_meat_batches**: Incoming raw meat batches
- **storage_logs**: Temperature and humidity monitoring
- **processing_batches**: Kofta processing batches
- **cooking_logs**: Cooking activities and temperatures
- **orders**: Customer orders
- **order_items**: Links orders to cooking logs
- **complaints**: Food poisoning incident reports

### Relationships

```
Supplier
  └── RawMeatBatch
        ├── StorageLog
        └── ProcessingBatch
              └── CookingLog
                    └── OrderItem
                          └── Order
                                └── Complaint
```

## 📖 Usage Guide

### 1. Getting Started

After installation, visit the dashboard at `http://localhost:8000/dashboard` to see:
- System statistics
- Risk alerts
- Recent activity
- Quick action buttons

### 2. Creating a Supplier

1. Navigate to **Suppliers** → **Add Supplier**
2. Fill in supplier details:
   - Name (required)
   - Contact person
   - Phone, email, address
   - License number
   - Status (active/inactive/suspended)
3. Click **Create**

### 3. Receiving Raw Meat

1. Go to **Raw Meat** → **Add Batch**
2. Enter batch information:
   - Batch number (unique)
   - Select supplier
   - Production date
   - Expiration date
   - Quantity in kg
   - Status
3. Click **Create**

### 4. Logging Storage Temperature

1. View a raw meat batch
2. Add storage logs with:
   - Temperature (°C)
   - Humidity (%)
   - Notes
   - Timestamp

**⚠️ Important**: Temperatures above 4°C will trigger risk alerts!

### 5. Creating Processing Batch (Kofta)

1. Go to **Processing** → **Add Batch**
2. Select a raw meat batch (must be "received" or "in_storage")
3. Enter:
   - Batch number
   - Production date
   - Expiration date
   - Quantity (number of kofta sticks)
   - Status
4. Click **Create**

The raw meat batch status will automatically update to "processing".

### 6. Logging Cooking Activity

1. Go to **Cooking** → **Log Cooking**
2. Select:
   - Processing batch
   - Quantity cooked
   - Cooking temperature (°C)
   - Cooking duration (minutes)
   - Optional: Link to an order
3. Click **Log Cooking**

**⚠️ Important**: Cooking temperatures below 75°C will trigger risk alerts!

### 7. Creating an Order

1. Go to **Orders** → **New Order**
2. Enter order details:
   - Order number
   - Customer name and phone
   - Status
3. Add order items:
   - Select cooking log
   - Enter quantity
   - Optional: Unit price
4. Click **Create Order**

### 8. Reporting a Complaint (Most Important Feature)

1. Go to **Complaints** → **Report Complaint**
2. Select the order related to the incident
3. Customer information will auto-populate
4. Enter:
   - Symptoms
   - Incident description
   - Incident date & time
   - Severity level
   - Status
5. Click **Report Complaint**

### 9. Viewing Traceability Report

1. Go to **Complaints** → Click on any complaint
2. View the **Complete Traceability Timeline** showing:
   - Supplier delivery
   - Storage logs with temperatures
   - Processing batch creation
   - Cooking activities
   - Order serving
   - Complaint incident
3. See all related batches and suppliers
4. **Export** the report as CSV if needed

## 🔄 Traceability Flow

### Complete Lifecycle Example

```
1. SUPPLIER DELIVERY
   Fresh Meat Co. delivers raw meat batch RMB-2024-001
   ↓
2. STORAGE
   Raw meat stored at 2.5°C (safe)
   ⚠️ Later: Temperature rises to 5.8°C (UNSAFE - Risk Alert!)
   ↓
3. PROCESSING
   Raw meat processed into kofta batch KFT-2024-001
   (200 kofta sticks created)
   ↓
4. COOKING
   Kofta cooked at 72°C for 12 minutes
   ⚠️ RISK: Undercooked (should be 75°C+, 15+ min)
   ↓
5. ORDER
   Order ORD-2024-002 created and served to Maria Garcia
   ↓
6. COMPLAINT
   Food poisoning incident reported
   ↓
7. TRACEABILITY
   System traces back through all steps to identify:
   - Source supplier: Fresh Meat Co.
   - Problematic raw meat batch: RMB-2024-001
   - Processing batch: KFT-2024-001
   - Risk points: High storage temp + undercooking
```

## 🎨 Key Pages

### Dashboard (`/dashboard`)
- System overview
- Statistics cards
- Risk alerts
- Recent activity
- Quick actions

### Suppliers (`/suppliers`)
- List all suppliers
- Search and filter
- View supplier details
- See all batches from supplier

### Raw Meat Batches (`/raw-meat-batches`)
- List all raw meat batches
- View batch details with storage logs
- See processing batches made from each batch

### Processing Batches (`/processing-batches`)
- List all kofta batches
- View batch details
- See cooking logs for each batch

### Cooking Logs (`/cooking`)
- List all cooking activities
- View cooking details with temperatures
- See linked orders

### Orders (`/orders`)
- List all customer orders
- View order details
- See linked complaints

### Complaints (`/complaints`) ⭐
- List all complaints
- Search and filter by severity/status
- **View complete traceability timeline**
- Export traceability reports

## 🔍 Search & Filter Features

### Suppliers
- Search by: name, contact person, license number
- Filter by: status (active/inactive/suspended)

### Complaints
- Search by: complaint number, customer name, symptoms
- Filter by: severity (low/medium/high/critical)
- Filter by: status (reported/investigating/resolved/closed)

## 📊 Risk Alerts

The dashboard automatically detects and alerts on:

1. **Expired Batches**: Processing batches past expiration date
2. **Unsafe Storage**: Temperatures above 4°C in last 7 days
3. **Undercooked Items**: Cooking temperatures below 75°C in last 7 days

## 📥 Export Functionality

### Export Complaint Traceability Report

1. View any complaint
2. Click **Export Report** button
3. Download CSV file containing:
   - Complaint details
   - Complete traceability chain
   - All related batches and suppliers
   - Risk points identified

## 🧪 Test Data

The system includes a comprehensive seeder with a realistic scenario:

- **Scenario**: Food poisoning incident traced back to supplier
- **Risk Points**: 
  - Storage temperature exceeded 4°C
  - Cooking temperature too low (72°C)
  - Cooking duration too short (12 min)

To view the test scenario:
1. Run the seeder: `php artisan db:seed --class=TraceabilityTestDataSeeder`
2. Visit: `http://localhost:8000/complaints/1`
3. See the complete traceability timeline

## 🛡️ Food Safety Standards

The system enforces and monitors:

- **Storage Temperature**: Should be ≤ 4°C
- **Cooking Temperature**: Should be ≥ 75°C
- **Cooking Duration**: Should be ≥ 15 minutes
- **Batch Expiration**: Automatic tracking and alerts

## 📱 Routes

### Main Routes

```
GET  /                          → Dashboard
GET  /dashboard                 → Dashboard
GET  /suppliers                 → List suppliers
POST /suppliers                 → Create supplier
GET  /suppliers/{id}            → View supplier
GET  /raw-meat-batches          → List raw meat batches
POST /raw-meat-batches          → Create raw meat batch
GET  /processing-batches         → List processing batches
POST /processing-batches         → Create processing batch
GET  /cooking                    → List cooking logs
POST /cooking                    → Create cooking log
GET  /orders                    → List orders
POST /orders                    → Create order
GET  /complaints                → List complaints
POST /complaints                → Create complaint
GET  /complaints/{id}           → View complaint with traceability
GET  /export/complaint/{id}     → Export complaint report
```

## 🔐 Security Notes

- Authentication is assumed to exist but not implemented in this version
- In production, add:
  - User authentication
  - Role-based access control
  - Audit logging
  - Data encryption for sensitive information

## 🐛 Troubleshooting

### Migration Errors

If you get foreign key constraint errors:
```bash
php artisan migrate:fresh
```

### Vite Manifest Error

The system uses Tailwind CSS via CDN, so no build step is required. If you see Vite errors, they should be resolved.

### Empty Dropdowns

Make sure you create data in order:
1. Suppliers first
2. Then raw meat batches
3. Then processing batches
4. Then cooking logs
5. Then orders
6. Finally complaints

## 📝 Code Structure

```
app/
├── Http/Controllers/
│   ├── DashboardController.php      # Dashboard statistics
│   ├── SupplierController.php        # Supplier CRUD
│   ├── RawMeatBatchController.php    # Raw meat batch CRUD
│   ├── ProcessingBatchController.php # Processing batch CRUD
│   ├── CookingController.php         # Cooking log CRUD
│   ├── OrderController.php           # Order CRUD
│   ├── ComplaintController.php       # Complaint CRUD + Traceability
│   └── ExportController.php          # Export functionality
├── Models/
│   ├── Supplier.php
│   ├── RawMeatBatch.php
│   ├── StorageLog.php
│   ├── ProcessingBatch.php
│   ├── CookingLog.php
│   ├── Order.php
│   ├── OrderItem.php
│   └── Complaint.php
database/
├── migrations/                       # All table migrations
└── seeders/
    └── TraceabilityTestDataSeeder.php # Test data seeder
resources/
└── views/
    ├── layouts/
    │   └── app.blade.php             # Main layout
    ├── dashboard.blade.php           # Dashboard
    ├── suppliers/                    # Supplier views
    ├── raw-meat-batches/             # Raw meat views
    ├── processing-batches/            # Processing views
    ├── cooking/                      # Cooking views
    ├── orders/                       # Order views
    └── complaints/                   # Complaint views (with traceability)
```

## 🚀 Future Enhancements

Potential features to add:

- [ ] QR code generation for batches
- [ ] Mobile app for field logging
- [ ] Email notifications for risk alerts
- [ ] PDF report generation
- [ ] Advanced analytics and reporting
- [ ] Multi-language support
- [ ] API endpoints for integration
- [ ] Real-time temperature monitoring integration
- [ ] Batch recall functionality
- [ ] Supplier performance tracking

## 📄 License

This project is open-source and available for use in food safety applications.

## 👥 Support

For issues, questions, or contributions, please refer to the project repository.

## 🎯 Quick Start Checklist

- [ ] Install dependencies: `composer install`
- [ ] Configure `.env` file with database credentials
- [ ] Run migrations: `php artisan migrate`
- [ ] (Optional) Seed test data: `php artisan db:seed --class=TraceabilityTestDataSeeder`
- [ ] Start server: `php artisan serve`
- [ ] Visit: `http://localhost:8000/dashboard`
- [ ] Explore the system and view test complaint traceability

---

**Built with ❤️ for Food Safety**
