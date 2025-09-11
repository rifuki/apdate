# 🚀 APDATE - Supabase Deployment Guide

## ✅ Pre-Deployment Checklist

Your APDATE system sudah **production-ready** dengan:
- ✅ **20 Menu** lengkap dan teraccess admin
- ✅ **PostgreSQL** compatibility (no MySQL code)
- ✅ **Boolean types** sudah fix 
- ✅ **bcrypt passwords** (secure)
- ✅ **Session management** optimal
- ✅ **Auto-setup script** (init-database.sql)

## 📋 Step-by-Step Deployment

### 1. Setup Supabase Project
1. Go to [supabase.com](https://supabase.com) → New Project
2. Choose: **Region** (Singapore recommended for Indonesia)
3. **Database Password**: buat password yang kuat
4. Wait for project creation (~2 minutes)

### 2. Get Database Credentials  
1. Project Settings → **Database**
2. Copy semua credentials:
   ```
   Host: db.xxxxxxxxx.supabase.co
   Database: postgres  
   User: postgres
   Password: [your-password]
   Port: 5432
   ```

### 3. Import Database (CRITICAL STEP)
Di Supabase **SQL Editor**, jalankan file ini **BERURUTAN**:

**STEP 1**: Copy-paste isi `database.sql` → Run
**STEP 2**: Copy-paste isi `init-database.sql` → Run  

⚠️ **PENTING**: Jangan skip `init-database.sql`! File ini yang memastikan:
- Admin punya akses ke semua 20 menu
- Boolean types benar
- Periode status optimal

### 4. Update Application Config
1. Copy `.env.supabase` ke `.env`
2. Update dengan credentials Supabase Anda:
   ```env
   DB_HOST=db.your-project-id.supabase.co
   DB_NAME=postgres
   DB_USER=postgres  
   DB_PASS=your-supabase-password
   CI_ENV=production
   ```

### 5. Deploy Application
Upload semua files ke hosting provider:
- **Railway** (recommended)
- **Render**  
- **Vercel** 
- **Heroku**
- **VPS/Shared Hosting**

### 6. Test Deployment
1. **Login**: `admin` / `admin123`
2. **Check**: Semua 20 menu muncul di sidebar
3. **Test**: Navigate antar menu tanpa error

## 🔧 Troubleshooting

**❌ Menu hilang/berkurang?**
```sql
-- Jalankan di Supabase SQL Editor:
UPDATE m_users_group 
SET menu_access = '1,2,3,4,5,6,7,8,10,33,34,35,36,37,38,39,40,41,42,43' 
WHERE id_grup = 1;
```

**❌ Boolean error?** 
```sql  
-- Pastikan init-database.sql sudah dijalankan
-- Atau jalankan manual:
UPDATE mt_periode_semester SET status = '1.1' WHERE is_active = TRUE;
```

**❌ Login gagal?**
- Password admin: `admin123` 
- Pastikan `database.sql` dan `init-database.sql` sudah diimport

## 📞 Support
Jika ada masalah, check:
1. Supabase logs untuk database errors
2. Hosting provider logs untuk application errors
3. Browser console untuk JavaScript errors

---
**🎯 Result**: APDATE berjalan di Supabase dengan semua 20 menu utuh dan sistem fully operational!