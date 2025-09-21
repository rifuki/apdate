# Docker Deployment Guide

## Prerequisites
- Docker (with Compose plugin included)

## Quick Start

1. **Clone and setup**
   ```bash
   git clone <repository>
   cd apdate
   ```

2. **Environment configuration**
   ```bash
   cp .env.example .env
   # Edit .env file with your settings
   ```

3. **Start services**
   ```bash
   docker compose up -d
   ```

4. **Access application**
   - Application: http://localhost:8080
   - phpMyAdmin: http://localhost:8081

## Services

### Application (port 8080)
- PHP 7.4 with Apache
- CodeIgniter 3.1.5
- All required PHP extensions

### Database (port 3306)
- MySQL 8.0
- Auto-imports DB_APDATE.sql
- Credentials in docker-compose.yml

### phpMyAdmin (port 8081)
- Database administration
- Login with root/root_password

## Directory Structure
```
docker/
├── apache-config.conf  # Apache virtual host
├── php.ini            # PHP configuration
├── database.php       # Database config for Docker
└── README.md         # This file
```

## Environment Variables

Key variables in docker-compose.yml:
- `DB_HOST=db`
- `DB_NAME=apdate`
- `DB_USER=apdate_user`
- `DB_PASS=apdate_password`

## Volumes

- `./upload` - File uploads (persistent)
- `./application/logs` - Application logs
- `mysql_data` - Database data (persistent)

## Commands

```bash
# Start services
docker compose up -d

# View logs
docker compose logs -f app

# Stop services
docker compose down

# Rebuild application
docker compose build app
docker compose up -d app

# Database backup
docker exec apdate-db mysqldump -u root -proot_password apdate > backup.sql

# Database restore
docker exec -i apdate-db mysql -u root -proot_password apdate < backup.sql
```

## Production Notes

1. **Change default passwords** in docker-compose.yml
2. **Set APP_ENV=production**
3. **Configure proper SMTP** settings
4. **Enable HTTPS** with reverse proxy
5. **Regular backups** of database and uploads

## Troubleshooting

**Permission issues:**
```bash
sudo chown -R www-data:www-data upload/
sudo chmod -R 755 upload/
```

**Database connection:**
- Ensure DB_HOST=db in environment
- Check docker compose services are running

**View container logs:**
```bash
docker compose logs app
docker compose logs db
```