#!/bin/bash

# SSL Certificate Setup Script for APDATE Production
# This script sets up Let's Encrypt SSL certificates for your domains

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
EMAIL="your-email@example.com"
MAIN_DOMAIN="example.com"
PHPMYADMIN_DOMAIN="phpmyadmin.example.com"

echo -e "${GREEN}=== APDATE SSL Setup ===${NC}"
echo -e "${YELLOW}This script will set up SSL certificates for your domains${NC}"
echo ""

# Check if domains are set
if [[ "$MAIN_DOMAIN" == "example.com" ]]; then
    echo -e "${RED}ERROR: Please edit this script and set your actual domains!${NC}"
    echo "Edit the following variables in this script:"
    echo "- EMAIL: Your email for Let's Encrypt"
    echo "- MAIN_DOMAIN: Your main domain (e.g., myapp.com)"
    echo "- PHPMYADMIN_DOMAIN: Your phpMyAdmin subdomain (e.g., admin.myapp.com)"
    exit 1
fi

echo -e "${YELLOW}Configuration:${NC}"
echo "Email: $EMAIL"
echo "Main Domain: $MAIN_DOMAIN"
echo "phpMyAdmin Domain: $PHPMYADMIN_DOMAIN"
echo ""

read -p "Continue with this configuration? (y/n): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Setup cancelled."
    exit 1
fi

# Function to check if domain resolves to current server
check_domain() {
    local domain=$1
    echo -e "${YELLOW}Checking DNS for $domain...${NC}"

    # Get server's public IP
    SERVER_IP=$(curl -s ipinfo.io/ip)

    # Get domain's IP
    DOMAIN_IP=$(dig +short $domain)

    if [[ "$DOMAIN_IP" == "$SERVER_IP" ]]; then
        echo -e "${GREEN}✓ $domain resolves to this server ($SERVER_IP)${NC}"
        return 0
    else
        echo -e "${RED}✗ $domain does not resolve to this server${NC}"
        echo "Domain IP: $DOMAIN_IP"
        echo "Server IP: $SERVER_IP"
        return 1
    fi
}

# Check DNS for both domains
echo -e "${YELLOW}=== DNS Check ===${NC}"
DNS_OK=true

if ! check_domain "$MAIN_DOMAIN"; then
    DNS_OK=false
fi

if ! check_domain "$PHPMYADMIN_DOMAIN"; then
    DNS_OK=false
fi

if [[ "$DNS_OK" != "true" ]]; then
    echo -e "${RED}DNS check failed! Please ensure your domains point to this server.${NC}"
    read -p "Continue anyway? (y/n): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
fi

# Start services without SSL first
echo -e "${YELLOW}=== Starting services for certificate generation ===${NC}"
docker-compose -f docker-compose.prod.yml down
docker-compose -f docker-compose.prod.yml up -d nginx

# Wait for nginx to be ready
echo "Waiting for nginx to be ready..."
sleep 10

# Get certificates for main domain
echo -e "${YELLOW}=== Getting SSL certificate for $MAIN_DOMAIN ===${NC}"
docker-compose -f docker-compose.prod.yml run --rm certbot \
    certonly --webroot --webroot-path=/var/www/certbot \
    --email $EMAIL \
    --agree-tos \
    --no-eff-email \
    -d $MAIN_DOMAIN \
    -d www.$MAIN_DOMAIN

# Get certificates for phpMyAdmin domain
echo -e "${YELLOW}=== Getting SSL certificate for $PHPMYADMIN_DOMAIN ===${NC}"
docker-compose -f docker-compose.prod.yml run --rm certbot \
    certonly --webroot --webroot-path=/var/www/certbot \
    --email $EMAIL \
    --agree-tos \
    --no-eff-email \
    -d $PHPMYADMIN_DOMAIN

# Update nginx config with actual domains
echo -e "${YELLOW}=== Updating nginx configuration ===${NC}"
sed -i "s/example.com/$MAIN_DOMAIN/g" docker/prod/sites/apdate.conf
sed -i "s/phpmyadmin.example.com/$PHPMYADMIN_DOMAIN/g" docker/prod/sites/apdate.conf

# Restart services with SSL
echo -e "${YELLOW}=== Restarting services with SSL ===${NC}"
docker-compose -f docker-compose.prod.yml down
docker-compose -f docker-compose.prod.yml up -d

echo -e "${GREEN}=== SSL Setup Complete! ===${NC}"
echo ""
echo -e "${GREEN}Your applications are now available at:${NC}"
echo "Main App: https://$MAIN_DOMAIN"
echo "phpMyAdmin: https://$PHPMYADMIN_DOMAIN"
echo ""
echo -e "${YELLOW}Important notes:${NC}"
echo "1. Certificates will auto-renew every 12 hours"
echo "2. Check logs: docker-compose -f docker-compose.prod.yml logs nginx"
echo "3. Restart nginx after any config changes: docker-compose -f docker-compose.prod.yml restart nginx"
echo ""
echo -e "${GREEN}Setup completed successfully!${NC}"