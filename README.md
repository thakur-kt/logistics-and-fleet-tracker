# 🚚 Logistics and Fleet Management System

A real-time logistics and fleet management system built using **Laravel** (backend) and **Vue.js** (frontend). This system provides role-based dashboards for Admins, Drivers, and Dispatchers, with features like live vehicle tracking and order management

---

## 🔧 Tech Stack

- **Backend**: Laravel 10+
- **Frontend**: Vue 3
- **Authentication**: Laravel Sanctum
- **Real-time**: Laravel WebSockets, Redis
- **Queues**: Redis + Laravel Horizon
- **Database**: MySQL
- **Maps**: Leaflet.js 
- **Permissions**: Spatie Laravel Permission

---

## ⚙️ Features

### 🧑‍💼 Admin
- Dashboard with key metrics
- Manage vehicles, drivers, and dispatchers
- Assign delivery orders
- Monitor real-time fleet movement
- Access reports & analytics
- Role-based access control

### 🚐 Driver
- View assigned delivery orders
- Update delivery status (e.g., en route, delivered)
- Real-time location sharing

### 📍 Dispatcher
- Track vehicles on the map
- Auto-assign orders via queue

### 🧠 System Logic
- Real-time vehicle tracking using Redis and WebSockets
- Queued delivery assignment (Redis Queues + Laravel Horizon)
- Event-based architecture

---

## 🚀 Getting Started

### Prerequisites

- PHP 8.1+
- Composer
- Node.js 18+
- MySQL 
- Redis
- Laravel CLI

---

### 🖥️ Backend Setup (Laravel)

```bash
git clone https://github.com/thakur-kt/logistics-and-fleet-tracker.git
cd logistics-and-fleet-tracker

# Install dependencies
composer install

# Environment setup
cp .env.example .env
php artisan key:generate

# Configure DB, Redis, etc. in .env
php artisan migrate --seed

# Start the backend
php artisan serve

```

### 🌐 Frontend Setup (Vue.js)
```bash
npm install
npm run dev

```
### 🔁 Redis & Queue Workers
```bash
# Start Redis
redis-server

# Start queue worker
php artisan queue:work

# Start Laravel Horizon
php artisan horizon

```
### 📦 API & Auth
-API secured using Laravel Sanctum
-Endpoints grouped by user role
-Follows RESTful standards

## 🔐 Access Control (ACL) Structure

This system uses **Spatie Laravel Permission** to implement ACL (Access Control List) with a structured, grouped permissions system.

### 🧱 Grouped Roles and Permissions

Permissions are grouped by modules/features for clarity and ease of management:

- **Vehicle Management**
  - `view vehicles`
  - `add vehicle`
  - `edit vehicle`
  - `delete vehicle`

- **Driver Management**
  - `view drivers`
  - `assign drivers`
  - `track drivers`

- **Orders**
  - `create order`
  - `assign order`
  - `update order status`

### 🧑‍💼 Role Assignment

Roles and their permissions:

- **Admin**: Full access to all modules.
- **Dispatcher**:
  - Vehicle tracking
  - Order assignment
- **Driver**:
  - Order status updates
  - Location sharing

### 🧩 Middleware Protection

Routes are grouped using middleware like `role:admin` or `permission:update order status` to ensure proper access control.

```php
Route::middleware(['role:admin'])->group(function () {
    Route::resource('vehicles', VehicleController::class);
});
