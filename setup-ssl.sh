#!/bin/bash

# SSL Setup Script for Apdate Application
# This script helps set up SSL certificates and switch to HTTPS configuration

echo "=== SSL Setup Script ==="

# Check if running with proper domain configuration
if [ -z "$DOMAIN" ] || [ -z "$PHPMYADMIN_DOMAIN" ]; then
    echo "Please set DOMAIN and PHPMYADMIN_DOMAIN environment variables"
    echo "Example:"
    echo "export DOMAIN=yourdomain.com"
    echo "export PHPMYADMIN_DOMAIN=phpmyadmin.yourdomain.com"
    exit 1
fi

echo "Setting up SSL for domains:"
echo "  Main domain: $DOMAIN"
echo "  phpMyAdmin domain: $PHPMYADMIN_DOMAIN"

# Step 1: Update nginx configuration with actual domains
echo "Step 1: Updating nginx configuration with your domains..."
sed -i "s/example\.com/$DOMAIN/g" /home/userku/apdate/docker/prod/sites/apdate.conf
sed -i "s/phpmyadmin\.example\.com/$PHPMYADMIN_DOMAIN/g" /home/userku/apdate/docker/prod/sites/apdate.conf

# Step 2: Restart nginx to apply domain changes
echo "Step 2: Restarting nginx..."
docker compose -f docker-compose.prod.yml restart nginx

# Step 3: Generate SSL certificates
echo "Step 3: Generating SSL certificates..."
echo "Running certbot for main domain..."
docker compose -f docker-compose.prod.yml exec certbot certbot certonly \
    --webroot \
    --webroot-path=/var/www/certbot \
    --email admin@$DOMAIN \
    --agree-tos \
    --no-eff-email \
    -d $DOMAIN \
    -d www.$DOMAIN

echo "Running certbot for phpMyAdmin domain..."
docker compose -f docker-compose.prod.yml exec certbot certbot certonly \
    --webroot \
    --webroot-path=/var/www/certbot \
    --email admin@$DOMAIN \
    --agree-tos \
    --no-eff-email \
    -d $PHPMYADMIN_DOMAIN

# Step 4: Switch to SSL configuration
echo "Step 4: Switching to SSL configuration..."
cd /home/userku/apdate/docker/prod/sites
mv apdate.conf apdate-http.conf
mv apdate-ssl.conf.disabled apdate.conf

# Update the SSL config with actual domains
sed -i "s/example\.com/$DOMAIN/g" apdate.conf
sed -i "s/phpmyadmin\.example\.com/$PHPMYADMIN_DOMAIN/g" apdate.conf

# Step 5: Restart nginx with SSL configuration
echo "Step 5: Restarting nginx with SSL configuration..."
docker compose -f docker-compose.prod.yml restart nginx

echo "=== SSL Setup Complete ==="
echo "Your application should now be available at:"
echo "  https://$DOMAIN"
echo "  https://$PHPMYADMIN_DOMAIN"
echo ""
echo "HTTP requests will be automatically redirected to HTTPS."