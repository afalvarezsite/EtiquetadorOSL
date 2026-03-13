#!/bin/sh

CERT_FILE="/etc/nginx/certs/server.crt"
SSL_CONF_TEMPLATE="/etc/nginx/ssl.conf.template"
SSL_CONF_DEST="/etc/nginx/conf.d/ssl.conf"

if [ -f "$CERT_FILE" ]; then
    echo "SSL Certificate found at $CERT_FILE. Enabling HTTPS..."
    cp "$SSL_CONF_TEMPLATE" "$SSL_CONF_DEST"
else
    echo "SSL Certificate NOT found at $CERT_FILE. Running HTTP only."
    rm -f "$SSL_CONF_DEST"
fi
