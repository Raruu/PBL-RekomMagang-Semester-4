#!/bin/sh

# Check if certificates already exist
if [ -f /etc/nginx/ssl/cert.pem ] && [ -f /etc/nginx/ssl/key.pem ]; then
    echo "SSL certificates already exist"
    exit 0
fi

# Generate SSL certificate
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/nginx/ssl/key.pem \
    -out /etc/nginx/ssl/cert.pem \
    -subj "/C=US/ST=State/L=City/O=Organization/CN=localhost"

# Set correct permissions
chmod 644 /etc/nginx/ssl/cert.pem
chmod 600 /etc/nginx/ssl/key.pem

echo "SSL certificates generated successfully"