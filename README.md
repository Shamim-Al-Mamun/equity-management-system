# Equity Management System

A Laravel-based API-first system for managing client equities, portfolios, holdings, reports, and audit trails.

---

## 🚀 Features

- User Authentication & Role Management (Admin, Manager, Analyst)  
- Client & Client Holdings CRUD with Excel Import  
- Live Stock Price Integration (Mock/API)  
- Comprehensive Reports (PDF & Excel exports)  
- Audit Trail with Spatie Activitylog  
- Notification System with Laravel Notifications & Queues  
- RESTful API with JWT Authentication  
- API Documentation with Swagger (L5 Swagger)  
- Background Jobs & Scheduling for updates and emails  

---

## 🛠 Requirements

- PHP >= 8.2  
- Composer  
- MySQL / MariaDB  
- Laravel 12  
- Node.js & NPM (optional, for frontend scaffolding)  
- Laravel Excel  
- Spatie Laravel Permission  
- Tymon JWT Auth  
- L5 Swagger  
- Queue driver (database/Redis for background jobs)  

---

## ⚙️ Setup & Installation

1. Clone the repository and navigate into it:

   ```bash
   git clone https://github.com/Shamim-Al-Mamun/equity-management-system.git
   cd equity-management-system

   composer install
   cp .env.example .env

   Edit .env to set your database and app settings

   php artisan key:generate

   php artisan jwt:secret

   php artisan migrate:fresh --seed

   php artisan serve
