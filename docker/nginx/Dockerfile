FROM nginx:alpine

RUN apk add --no-cache certbot certbot-nginx openssl wget dcron

RUN mkdir -p /etc/letsencrypt/renewal-hooks/deploy /var/log/cron /etc/nginx/ssl

WORKDIR /etc/nginx

RUN echo "0 3 * * * /usr/bin/certbot renew --quiet --no-self-upgrade >> /var/log/cron/certbot-renew.log 2>&1" | crontab -

COPY docker/nginx/entrypoint.sh /docker-entrypoint.sh
RUN chmod +x /docker-entrypoint.sh

EXPOSE 80 443

ENTRYPOINT ["/docker-entrypoint.sh"]
