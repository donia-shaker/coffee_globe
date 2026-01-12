FROM nginx:alpine

RUN apk add --no-cache certbot certbot-nginx openssl wget dcron

RUN mkdir -p /etc/letsencrypt/renewal-hooks/deploy /var/log/cron

WORKDIR /etc/nginx

RUN echo "0 3 * * * /usr/bin/certbot renew --quiet --no-self-upgrade >> /var/log/cron/certbot-renew.log 2>&1" | crontab -

EXPOSE 80 443

CMD ["sh", "-c", "chmod +x /etc/letsencrypt/renewal-hooks/deploy/certbot-renewal.sh 2>/dev/null || true && crond -f -d 8 & nginx -g 'daemon off;'"]
