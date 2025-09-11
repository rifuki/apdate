# 🚀 APDATE - Railway Deployment Guide

## ✅ Pre-Deployment Checklist
- ✅ Database di Supabase ready
- ✅ Admin user: `admin` / `admin123` 
- ✅ All 20 menus configured
- ✅ Environment config ready

## 📋 Step-by-Step Deployment

### 1. Push to GitHub
```bash
# Initialize git (if not done)
git init
git add .
git commit -m "APDATE ready for Railway deployment"

# Add your GitHub repo
git remote add origin https://github.com/YOUR-USERNAME/apdate.git
git branch -M main
git push -u origin main
```

### 2. Railway Setup
1. Go to [railway.app](https://railway.app)
2. Sign up with GitHub
3. Click "New Project"
4. Select "Deploy from GitHub repo"  
5. Choose your APDATE repository
6. Click "Deploy"

### 3. Configure Environment Variables
In Railway dashboard → Variables tab, add:

```env
DB_HOST=aws-1-ap-southeast-1.pooler.supabase.com
DB_NAME=postgres
DB_USER=postgres.juesouqruobekqgcfdzz
DB_PASS=MDFbU0R7HDxbWLMt
DB_PORT=5432
CI_ENV=production
```

### 4. Custom Domain (Optional)
1. Railway dashboard → Settings
2. Add your custom domain
3. Update DNS records as instructed

## 🎯 Expected Result
- **URL**: `https://your-app.up.railway.app`
- **Login**: `admin` / `admin123`
- **Features**: All 20 menus working
- **Database**: Connected to Supabase

## 🔧 Troubleshooting
- **500 Error**: Check logs for missing PHP extensions
- **Database Error**: Verify Supabase credentials
- **403 Error**: Check file permissions in logs

## 📞 Support
Railway has excellent docs and Discord support for deployment issues.

---
**Result**: APDATE running live on Railway with Supabase database! 🚀