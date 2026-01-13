#!/bin/bash

cd /opt/coffee_globe

git pull

docker compose down nginx

docker compose up -d nginx
sleep 10

make ssl-fix
