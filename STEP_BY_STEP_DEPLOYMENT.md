# 📚 Step-by-Step Production Deployment ke VPS

## 🎯 Ringkasan Langkah

1. **Siapkan VPS** → Install Docker
2. **Setup Domain** → Point DNS ke VPS
3. **Upload Code** → Git clone atau upload
4. **Konfigurasi** → Edit .env production
5. **Deploy** → Run script SSL + Docker
6. **Test** → Akses via domain

---

## 📋 LANGKAH 1: Persiapan VPS

### 1.1 Login ke VPS
```bash
# SSH ke VPS Anda
ssh root@your-vps-ip
# atau
ssh your-username@your-vps-ip
```

### 1.2 Update System
```bash
# Update package list
sudo apt update && sudo apt upgrade -y

# Install basic tools
sudo apt install -y curl wget git nano htop
```

### 1.3 Install Docker
```bash
# Download Docker installation script
curl -fsSL https://get.docker.com -o get-docker.sh

# Install Docker
sudo sh get-docker.sh

# Add user to docker group (supaya tidak perlu sudo)
sudo usermod -aG docker $USER

# Verify Docker
docker --version
```

### 1.4 Verify Docker Compose
```bash
# Docker Compose is now included as a plugin with Docker
# No separate installation needed

# Verify Docker Compose is available
docker compose version
```

### 1.5 Setup Firewall (Optional tapi Recommended)
```bash
# Install ufw
sudo apt install ufw

# Allow SSH (PENTING!)
sudo ufw allow ssh
sudo ufw allow 22

# Allow HTTP & HTTPS
sudo ufw allow 80
sudo ufw allow 443

# Enable firewall
sudo ufw enable

# Check status
sudo ufw status
```

---

## 🌐 LANGKAH 2: Setup Domain

### 2.1 Beli Domain (jika belum punya)
- Beli di Namecheap, GoDaddy, Cloudflare, dll
- Contoh: `myapp.com`

### 2.2 Point DNS ke VPS
Login ke DNS management domain Anda, tambahkan record:

```
Type: A Record
Name: @
Value: your-vps-ip-address
TTL: 300

Type: A Record
Name: www
Value: your-vps-ip-address
TTL: 300

Type: A Record
Name: admin
Value: your-vps-ip-address
TTL: 300
```

### 2.3 Verify DNS (tunggu 5-30 menit)
```bash
# Test dari komputer lokal
dig myapp.com
dig www.myapp.com
dig admin.myapp.com

# Atau pakai online tools
# https://www.whatsmydns.net/
```

---

## 📤 LANGKAH 3: Upload Code ke VPS

### Option A: Git Clone (Recommended)
```bash
# Clone repository
git clone https://github.com/your-username/apdate.git
cd apdate
```

### Option B: Upload via SCP
```bash
# Dari komputer lokal
scp -r ./apdate root@your-vps-ip:/home/
ssh root@your-vps-ip
cd /home/apdate
```

### Option C: Upload via FTP/SFTP
- Gunakan FileZilla, WinSCP, atau tools lain
- Upload folder apdate ke VPS

---

## ⚙️ LANGKAH 4: Konfigurasi Production

### 4.1 Copy Environment Template
```bash
cd /path/to/apdate
cp .env.production.example .env
```

### 4.2 Edit Konfigurasi Production
```bash
nano .env
```

**Edit variabel ini (WAJIB!):**
```bash
# ==============================================
# GANTI SEMUA YANG ADA "CHANGE" atau "example"
# ==============================================

# Domain Configuration (GANTI!)
MAIN_DOMAIN=myapp.com
PHPMYADMIN_DOMAIN=admin.myapp.com
BASE_URL=https://myapp.com

# Database Security (GANTI PASSWORDS!)
DB_PASS=SuperSecurePassword123!
DB_USER_PASS=AnotherSecurePassword456!

# Application Security (GANTI!)
ENCRYPTION_KEY=RandomString32CharactersLong123

# Email Configuration (GANTI!)
SMTP_USER=your-email@gmail.com
SMTP_PASS=your-app-specific-password
SMTP_FROM=your-email@gmail.com
```

**Cara generate encryption key yang aman:**
```bash
# Generate random 32 character string
openssl rand -base64 32
# atau
head /dev/urandom | tr -dc A-Za-z0-9 | head -c 32
```

### 4.3 Make Script Executable (No need to edit script - it reads from .env!)
```bash
chmod +x docker/prod/ssl-setup.sh
```

---

## 🚀 LANGKAH 5: Deploy Production

### 5.1 Test Configuration
```bash
# Test docker compose config
docker compose -f docker-compose.prod.yml config

# Should show no errors
```

### 5.2 Run SSL Setup Script (Automatic)
```bash
# Jalankan script SSL otomatis
./docker/prod/ssl-setup.sh
```

Script ini akan:
1. ✅ Check DNS resolution
2. ✅ Start nginx untuk certificate validation
3. ✅ Get SSL certificates dari Let's Encrypt
4. ✅ Update nginx config dengan domain Anda
5. ✅ Restart semua services dengan SSL

### 5.3 Manual Deployment (jika script bermasalah)
```bash
# Build dan start services
docker compose -f docker-compose.prod.yml build
docker compose -f docker-compose.prod.yml up -d

# Get SSL certificates manual
docker compose -f docker-compose.prod.yml run --rm certbot \
    certonly --webroot --webroot-path=/var/www/certbot \
    --email your-email@gmail.com \
    --agree-tos --no-eff-email \
    -d myapp.com -d www.myapp.com

docker compose -f docker-compose.prod.yml run --rm certbot \
    certonly --webroot --webroot-path=/var/www/certbot \
    --email your-email@gmail.com \
    --agree-tos --no-eff-email \
    -d admin.myapp.com

# Update nginx config
sed -i 's/example.com/myapp.com/g' docker/prod/sites/apdate.conf
sed -i 's/phpmyadmin.example.com/admin.myapp.com/g' docker/prod/sites/apdate.conf

# Restart with SSL
docker compose -f docker-compose.prod.yml restart
```

---

## ✅ LANGKAH 6: Testing & Verification

### 6.1 Check Services Running
```bash
# Check all containers
docker compose -f docker-compose.prod.yml ps

# Should show all services as "Up"
```

### 6.2 Test HTTP to HTTPS Redirect
```bash
# Test main domain
curl -I http://myapp.com
# Should return: HTTP/1.1 301 Moved Permanently
# Location: https://myapp.com

# Test phpMyAdmin
curl -I http://admin.myapp.com
# Should redirect to https://admin.myapp.com
```

### 6.3 Test HTTPS Access
```bash
# Test main app
curl -I https://myapp.com
# Should return: HTTP/2 200

# Test phpMyAdmin
curl -I https://admin.myapp.com
# Should return: HTTP/2 200
```

### 6.4 Test via Browser
1. **Main App**: https://myapp.com
   - Should show APDATE login page
   - SSL certificate should be valid (green lock)

2. **phpMyAdmin**: https://admin.myapp.com
   - Should show phpMyAdmin login
   - SSL certificate should be valid

### 6.5 Check Logs
```bash
# Application logs
docker compose -f docker-compose.prod.yml logs app

# Nginx logs
docker compose -f docker-compose.prod.yml logs nginx

# Database logs
docker compose -f docker-compose.prod.yml logs db
```

---

## 🔧 MAINTENANCE COMMANDS

### Update Application
```bash
# Pull latest code
git pull origin main

# Rebuild and restart
docker compose -f docker-compose.prod.yml build
docker compose -f docker-compose.prod.yml up -d
```

### Database Backup
```bash
# Backup database
docker compose -f docker-compose.prod.yml exec db mysqldump -u root -p"$DB_PASS" apdate > backup_$(date +%Y%m%d).sql

# Restore backup
docker compose -f docker-compose.prod.yml exec -i db mysql -u root -p"$DB_PASS" apdate < backup_20231215.sql
```

### SSL Certificate Renewal (automatic)
```bash
# Certificates auto-renew every 12 hours via cron job in container
# Manual renewal:
docker compose -f docker-compose.prod.yml run --rm certbot renew
```

### Monitor Resources
```bash
# Check resource usage
docker stats

# Check disk space
df -h

# Check memory
free -h
```

---

## 🆘 TROUBLESHOOTING

### SSL Certificate Issues
```bash
# Check certificates exist
docker compose -f docker-compose.prod.yml exec nginx ls -la /etc/letsencrypt/live/

# Regenerate certificates
docker compose -f docker-compose.prod.yml run --rm certbot delete
./docker/prod/ssl-setup.sh
```

### Domain Not Resolving
```bash
# Check DNS from VPS
dig myapp.com @8.8.8.8
nslookup myapp.com

# Check nginx config
docker compose -f docker-compose.prod.yml exec nginx nginx -t
```

### Database Connection Issues
```bash
# Check database variables
docker compose -f docker-compose.prod.yml exec app env | grep DB_

# Test database connection
docker compose -f docker-compose.prod.yml exec app php -r "
\$conn = new mysqli(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASS'), getenv('DB_NAME'));
echo \$conn->connect_error ? 'Failed: '.\$conn->connect_error : 'Connected successfully';
"
```

### Application 500 Errors
```bash
# Check application logs
docker compose -f docker-compose.prod.yml logs app

# Check file permissions
docker compose -f docker-compose.prod.yml exec app ls -la upload/
docker compose -f docker-compose.prod.yml exec app ls -la application/logs/
```

---

## 🎉 SUCCESS!

Jika semua langkah berhasil, aplikasi Anda sekarang bisa diakses via:

- **Main Application**: https://myapp.com ✅
- **phpMyAdmin**: https://admin.myapp.com ✅
- **Automatic HTTPS** dengan SSL certificates ✅
- **Professional production setup** ✅

**Tidak ada lagi akses via port - semua melalui domain yang proper!** 🚀

### Next Steps:
1. Setup monitoring (optional)
2. Configure automated backups
3. Setup staging environment
4. Configure CDN (optional)

Selamat! Production deployment berhasil! 🎊