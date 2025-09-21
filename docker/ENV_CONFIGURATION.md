# Environment Configuration Guide

## ✅ Masalah Fixed: No More Hardcoded Values!

Sebelumnya ada masalah dengan hardcoded values di `docker-compose.yml` yang membuat konfigurasi tidak fleksibel. Sekarang semua sudah menggunakan `.env` file dengan proper defaults.

## 🎯 Keunggulan Konfigurasi Baru

### ✅ Single Source of Truth
- Semua konfigurasi ada di `.env` file
- Tidak perlu edit di multiple places
- Default values dengan fallback

### ✅ Environment Variables Support
```yaml
# Before (Hardcoded)
ports:
  - "8080:80"
environment:
  - DB_HOST=db
  - DB_PASS=root_password

# After (Environment-based)
ports:
  - "${APP_PORT:-8080}:80"
environment:
  - DB_HOST=${DB_HOST:-db}
  - DB_PASS=${DB_PASS:-root_password}
```

## 📁 File Structure

```
.env.example     # Template with defaults
.env            # Your local configuration
docker-compose.yml  # Uses env variables
```

## 🔧 Key Environment Variables

### Port Configuration
```bash
APP_PORT=8080          # Application port
DB_PORT=3306           # Database port
PHPMYADMIN_PORT=8081   # phpMyAdmin port
```

### Database Configuration
```bash
DB_HOST=db             # Database hostname (for Docker)
DB_NAME=apdate         # Database name
DB_USER=root           # Database user
DB_PASS=root_password  # Database password
```

### Application Settings
```bash
APP_ENV=development    # Environment (development/production)
BASE_URL=http://localhost:8080
ENCRYPTION_KEY=apdatev2
```

### Project Configuration
```bash
COMPOSE_PROJECT_NAME=apdate  # Docker project name
```

## 🚀 Usage Examples

### Development Setup
```bash
# Copy template
cp .env.example .env

# Start with defaults
docker-compose up -d
```

### Production Setup
```bash
# Copy template
cp .env.example .env

# Edit for production
APP_ENV=production
APP_PORT=80
DB_PASS=secure_password_here
ENCRYPTION_KEY=random_32_character_string

# Deploy
docker-compose up -d
```

### Custom Ports (Development)
```bash
# Edit .env
APP_PORT=9000
PHPMYADMIN_PORT=9001
DB_PORT=3307

# Restart
docker-compose down && docker-compose up -d

# Access
# App: http://localhost:9000
# phpMyAdmin: http://localhost:9001
```

### Multiple Environments
```bash
# Development
cp .env.example .env.dev
COMPOSE_PROJECT_NAME=apdate-dev
APP_PORT=8080

# Staging
cp .env.example .env.staging
COMPOSE_PROJECT_NAME=apdate-staging
APP_PORT=8081

# Use specific env file
docker-compose --env-file .env.dev up -d
docker-compose --env-file .env.staging up -d
```

## 🔒 Security Best Practices

### Production Configuration
```bash
# Strong passwords
DB_PASS=$(openssl rand -base64 32)
DB_USER_PASS=$(openssl rand -base64 32)

# Random encryption key
ENCRYPTION_KEY=$(openssl rand -base64 32)

# Production environment
APP_ENV=production
CI_ENV=production
```

### Development vs Production
```bash
# Development (.env.dev)
APP_ENV=development
APP_PORT=8080
DB_PASS=root_password

# Production (.env.prod)
APP_ENV=production
APP_PORT=80
DB_PASS=secure_random_password
```

## 📋 Migration Guide

### From Hardcoded to Environment-based

1. **Backup existing config**
   ```bash
   cp docker-compose.yml docker-compose.yml.backup
   ```

2. **Create .env file**
   ```bash
   cp .env.example .env
   ```

3. **Customize values**
   ```bash
   # Edit .env with your specific values
   nano .env
   ```

4. **Test configuration**
   ```bash
   docker-compose config  # Verify interpolation
   docker-compose up -d   # Deploy
   ```

## 🎯 Benefits Summary

| Sebelum | Sesudah |
|---------|---------|
| ❌ Hardcoded values | ✅ Environment variables |
| ❌ Edit multiple files | ✅ Single .env file |
| ❌ No fallback defaults | ✅ Default values with fallback |
| ❌ Same config for all environments | ✅ Environment-specific configs |
| ❌ Manual port management | ✅ Easy port customization |

## 💡 Pro Tips

1. **Never commit .env** - Add to `.gitignore`
2. **Use .env.example** - For team consistency
3. **Validate config** - Use `docker-compose config`
4. **Environment naming** - Use descriptive project names
5. **Port conflicts** - Easy to change via .env

Sekarang user hanya perlu edit **satu file** (`.env`) untuk mengubah semua konfigurasi! 🎉