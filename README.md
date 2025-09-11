# APDATE - Educational Management System

Educational management system built with CodeIgniter 3.1.5 + AdminLTE template, featuring multi-role support (Admin/Guru/Siswa).

## 🚀 Quick Start - One Command Setup

### Prerequisites
- Docker & Docker Compose installed

### Ready-to-Use Setup
```bash
# Clone and start - THAT'S IT!
docker-compose up -d

# Wait ~2-3 minutes for complete setup, then open:
# 🌐 http://localhost:8080/login_dashboard
```

### 🎯 Automatic Setup Includes:
- ✅ **Full PostgreSQL database** with all tables & data
- ✅ **All 20 admin menus** automatically configured  
- ✅ **Production-ready admin user** (no manual fixes needed)
- ✅ **All PHP extensions** (PostgreSQL, GD, ZIP, etc.)
- ✅ **Composer dependencies** installed
- ✅ **Proper file permissions** set

### 🔗 Access URLs  
- **Application**: http://localhost:8080
- **Admin Login**: http://localhost:8080/login_dashboard
- **pgAdmin**: http://localhost:8081
- **PostgreSQL**: localhost:5432

### 🔑 Default Login
- **Admin User**: `admin` / `admin123` (Full access to all 20 menus)
- **pgAdmin**: `admin@admin.com` / `admin123`  
- **Database**: `apdate` (`postgres` / `postgres123`)

## 🏗️ Architecture
- **Backend**: PHP 8.0 + CodeIgniter 3.1.5
- **Frontend**: AdminLTE 3.x
- **Database**: PostgreSQL 13
- **Container**: Docker + Docker Compose

## 🔧 Development
```bash
# Stop containers
docker-compose down

# Rebuild after changes
docker-compose up -d --build

# View logs
docker-compose logs -f web
```

## 📝 Features
- Multi-role authentication (Admin/Guru/Siswa)
- Class management
- Student management  
- Teacher management
- Grade management
- Academic period management
- Reports and analytics

## 🌐 Deploy to Supabase

### 1. Setup Supabase Database
1. Create new project at [supabase.com](https://supabase.com)
2. Go to Settings > Database and copy connection details
3. In Supabase SQL Editor, run `database.sql` file

### 2. Configure Environment
Copy `.env.example` to `.env` and update:
```env
# Supabase Database
DB_HOST=db.your-project.supabase.co
DB_NAME=postgres
DB_USER=postgres
DB_PASS=your-supabase-password
```

### 3. Install Dependencies
```bash
composer install --no-dev
```

### 4. Deploy
Upload files to your hosting provider (Railway, Render, etc.) with PHP 8.0+ support.