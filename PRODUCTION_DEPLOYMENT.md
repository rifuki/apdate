# 🚀 Production Deployment Guide - VPS dengan Domain & HTTPS

## 📋 Ringkasan Solusi

**Masalah**: Tidak bisa akses production via port, harus pakai domain dengan reverse proxy.

**Solusi**: Nginx reverse proxy + Let's Encrypt SSL + Domain-based access.

### ✅ Yang Disediakan:
- **Nginx reverse proxy** untuk routing domain
- **Automatic HTTPS** dengan Let's Encrypt
- **Rate limiting** untuk security
- **Production-optimized** configuration
- **Automated SSL renewal**

## 🏗️ Arsitektur Production

```
Internet → Domain → Nginx (80/443) → Docker Apps
                                   ├── APDATE App (:80)
                                   └── phpMyAdmin (:80)
```

### 🌐 Domain Setup:
- **Main App**: `https://yourdomain.com`
- **phpMyAdmin**: `https://admin.yourdomain.com`

## 🛠️ Quick Setup Guide

### 1. Persiapan VPS

```bash
# Update sistem
sudo apt update && sudo apt upgrade -y

# Install Docker & Docker Compose
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/download/v2.20.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Reboot untuk apply group changes
sudo reboot
```

### 2. Setup Domain & DNS

```bash
# Point domain DNS ke VPS IP
A Record: yourdomain.com → your.vps.ip.address
A Record: www.yourdomain.com → your.vps.ip.address
A Record: admin.yourdomain.com → your.vps.ip.address
```

### 3. Deploy Application

```bash
# Clone & masuk ke direktori
git clone <your-repo>
cd apdate

# Setup production environment
cp .env.production .env

# Edit konfigurasi (WAJIB!)
nano .env
```

**Edit variabel penting di `.env`:**
```bash
# Domain configuration
MAIN_DOMAIN=yourdomain.com
PHPMYADMIN_DOMAIN=admin.yourdomain.com
BASE_URL=https://yourdomain.com

# Security (GANTI SEMUA!)
DB_PASS=super_secure_password_123
DB_USER_PASS=another_secure_password_456
ENCRYPTION_KEY=random32characterstringhere123

# Email for SSL certificates
SMTP_USER=youremail@gmail.com
```

### 4. Setup SSL & Deploy

```bash
# Edit SSL script dengan domain Anda
nano docker/prod/ssl-setup.sh
# Update EMAIL, MAIN_DOMAIN, PHPMYADMIN_DOMAIN

# Jalankan setup SSL otomatis
./docker/prod/ssl-setup.sh

# Atau manual jika ada issue:
docker-compose -f docker-compose.prod.yml up -d
```

## 🔧 File Structure Production

```
docker-compose.prod.yml     # Production compose
.env.production            # Production environment template
docker/prod/
├── nginx.conf             # Main nginx config
├── sites/apdate.conf      # Site-specific config
├── mysql.cnf              # MySQL optimization
└── ssl-setup.sh           # SSL automation script
```

## 🌐 Access URLs

| Service | Development | Production |
|---------|-------------|------------|
| Main App | http://localhost:8080 | https://yourdomain.com |
| phpMyAdmin | http://localhost:8081 | https://admin.yourdomain.com |
| Database | localhost:3306 | Internal only |

## 🔒 Security Features

### ✅ HTTPS/SSL
- **Automatic SSL** dengan Let's Encrypt
- **Force HTTPS** redirect
- **Auto-renewal** setiap 12 jam
- **Strong cipher suites**

### ✅ Rate Limiting
```nginx
# Login protection
location ~ ^/(siswa|guru|admin)/login {
    limit_req zone=login burst=3 nodelay;
}

# General protection
location / {
    limit_req zone=general burst=20 nodelay;
}
```

### ✅ Security Headers
- HSTS (HTTP Strict Transport Security)
- X-Frame-Options: DENY
- X-XSS-Protection
- Content Security Policy

### ✅ Optional IP Restriction (phpMyAdmin)
```nginx
# Uncomment dan set IP Anda
# allow 203.0.113.0/24;  # Your IP range
# deny all;
```

## 🚀 Deployment Commands

### Start Production
```bash
# Full deployment
docker-compose -f docker-compose.prod.yml up -d

# Check status
docker-compose -f docker-compose.prod.yml ps

# View logs
docker-compose -f docker-compose.prod.yml logs -f
```

### SSL Management
```bash
# Manual SSL renewal
docker-compose -f docker-compose.prod.yml run --rm certbot renew

# Check certificates
docker-compose -f docker-compose.prod.yml exec nginx ls -la /etc/letsencrypt/live/
```

### Maintenance
```bash
# Update application
docker-compose -f docker-compose.prod.yml pull
docker-compose -f docker-compose.prod.yml up -d --build

# Backup database
docker-compose -f docker-compose.prod.yml exec db mysqldump -u root -p apdate > backup.sql

# View nginx logs
docker-compose -f docker-compose.prod.yml logs nginx
```

## 🎯 Production Checklist

### ☑️ Pre-Deployment
- [ ] Domain DNS pointing to VPS IP
- [ ] VPS dengan Docker installed
- [ ] Firewall configured (ports 80, 443, 22)

### ☑️ Configuration
- [ ] `.env` file configured dengan domain
- [ ] Strong passwords untuk database
- [ ] Random encryption key (32 chars)
- [ ] Email settings untuk notifikasi

### ☑️ Security
- [ ] SSL certificates installed
- [ ] HTTPS redirect working
- [ ] Rate limiting configured
- [ ] Optional: IP whitelist untuk phpMyAdmin

### ☑️ Post-Deployment
- [ ] Test main app: `https://yourdomain.com`
- [ ] Test phpMyAdmin: `https://admin.yourdomain.com`
- [ ] Check SSL: `curl -I https://yourdomain.com`
- [ ] Monitor logs for errors

## 🆘 Troubleshooting

### SSL Issues
```bash
# Check certificates
docker-compose -f docker-compose.prod.yml exec nginx ls -la /etc/letsencrypt/live/

# Regenerate certificates
docker-compose -f docker-compose.prod.yml run --rm certbot delete
./docker/prod/ssl-setup.sh
```

### Domain Access Issues
```bash
# Check nginx config
docker-compose -f docker-compose.prod.yml exec nginx nginx -t

# Check DNS resolution
dig yourdomain.com
nslookup yourdomain.com
```

### Application Issues
```bash
# Check app logs
docker-compose -f docker-compose.prod.yml logs app

# Check database connection
docker-compose -f docker-compose.prod.yml exec app php -r "echo 'DB: ' . getenv('DB_HOST');"
```

## 📊 Monitoring

### Log Locations
```bash
# Application logs
docker-compose -f docker-compose.prod.yml logs app

# Nginx access/error logs
docker-compose -f docker-compose.prod.yml logs nginx

# Database logs
docker-compose -f docker-compose.prod.yml logs db
```

### Health Checks
```bash
# Quick health check
curl -I https://yourdomain.com
curl -I https://admin.yourdomain.com

# Database health
docker-compose -f docker-compose.prod.yml exec db mysqladmin ping
```

## 🎉 Success!

Setelah setup berhasil, aplikasi Anda akan accessible via:

- **Main Application**: https://yourdomain.com ✅
- **phpMyAdmin**: https://admin.yourdomain.com ✅
- **Automatic HTTPS** dengan SSL certificates ✅
- **Production-grade security** ✅
- **Rate limiting & protection** ✅

**No more port access - everything via domain dengan reverse proxy!** 🚀