# 📦 Composer Dependencies Guide

## تثبيت المكتبات التلقائي

### في `docker/entrypoint.sh`:

```bash
# يتحقق من وجود vendor قبل كل بدء
if [ ! -d "vendor" ] || [ ! -f "vendor/autoload.php" ]; then
    composer install --no-dev --optimize-autoloader
fi
```

**النتيجة:** المكتبات تُثبت تلقائياً عند كل بدء إذا كانت غير موجودة

## المكتبات المطلوبة

### Production Dependencies (`composer.json`)

```json
{
    "require": {
        "php": "^8.2",
        "donia-shaker/media-library": "^2.0",
        "guzzlehttp/guzzle": "^7.9",
        "inertiajs/inertia-laravel": "^2.0",
        "laravel/framework": "^12.0",
        "laravel/sanctum": "^4.0",
        "laravel/tinker": "^2.10.1",
        "spatie/laravel-permission": "^6.16",
        "tightenco/ziggy": "^2.0"
    }
}
```

### 1. **Laravel Framework** (`laravel/framework: ^12.0`)
- الإطار الأساسي للتطبيق
- يوفر: Routing, Controllers, Models, Views, etc.

### 2. **Media Library** (`donia-shaker/media-library: ^2.0`)
- إدارة الملفات والصور
- رفع، حذف، تحسين الصور
- دعم FFmpeg للفيديو

### 3. **Guzzle HTTP** (`guzzlehttp/guzzle: ^7.9`)
- عميل HTTP للطلبات الخارجية
- API calls, webhooks

### 4. **Inertia.js** (`inertiajs/inertia-laravel: ^2.0`)
- بناء SPAs مع Laravel
- Vue.js integration
- Server-side routing

### 5. **Laravel Sanctum** (`laravel/sanctum: ^4.0`)
- مصادقة API tokens
- SPA authentication
- Mobile app authentication

### 6. **Laravel Tinker** (`laravel/tinker: ^2.10.1`)
- REPL للتفاعل مع التطبيق
- Debugging و testing

### 7. **Spatie Permissions** (`spatie/laravel-permission: ^6.16`)
- إدارة الصلاحيات والأدوار
- Roles & Permissions system

### 8. **Ziggy** (`tightenco/ziggy: ^2.0`)
- استخدام Laravel routes في JavaScript
- Frontend route generation

## PHP Extensions المطلوبة

### Core Extensions
```
✓ pdo              - Database connections
✓ pdo_mysql        - MySQL driver
✓ mbstring         - Multi-byte string support
✓ openssl          - Encryption
✓ tokenizer        - PHP tokenizer
✓ xml              - XML parsing
✓ ctype            - Character type checks
✓ json             - JSON encoding/decoding
```

### Laravel Required
```
✓ bcmath           - Precision mathematics
✓ zip              - ZIP archive handling
✓ intl             - Internationalization
✓ gd               - Image processing
✓ curl             - HTTP requests
✓ fileinfo         - File type detection
```

### Custom Requirements
```
✓ redis            - Redis cache driver
✓ imagick          - Advanced image processing
✓ soap             - SOAP protocol support
✓ sockets          - Socket operations
```

## التحقق من المكتبات

### طريقة 1: استخدام Make Command
```bash
make check-deps
```

**يتحقق من:**
- ✅ جميع PHP extensions
- ✅ جميع Composer packages
- ✅ وجود vendor/autoload.php
- ✅ إصدارات المكتبات

### طريقة 2: استخدام السكريبت مباشرة
```bash
docker exec coffee_globe_php bash docker/check-dependencies.sh
```

### طريقة 3: التحقق اليدوي
```bash
# PHP extensions
docker exec coffee_globe_php php -m

# Composer packages
docker exec coffee_globe_php composer show

# Specific package
docker exec coffee_globe_php composer show laravel/framework
```

## تثبيت المكتبات

### في Production

**تلقائي في `entrypoint.sh`:**
```bash
# يعمل عند كل docker compose up
if [ ! -d "vendor" ]; then
    composer install --no-dev --optimize-autoloader
fi
```

**في `SERVER_DEPLOY.sh`:**
```bash
# مع retry mechanism
docker exec coffee_globe_php composer install --no-dev --optimize-autoloader || {
    docker exec coffee_globe_php composer clear-cache
    docker exec coffee_globe_php composer install --no-dev --optimize-autoloader
}
```

### يدوي
```bash
# داخل الـ container
make composer-install

# أو مباشرة
docker exec coffee_globe_php composer install --no-dev --optimize-autoloader
```

### في Development
```bash
# مع dev dependencies
docker exec coffee_globe_php composer install
```

## تحديث المكتبات

### تحديث كل المكتبات
```bash
make composer-update
```

### تحديث مكتبة محددة
```bash
docker exec coffee_globe_php composer update spatie/laravel-permission
```

### التحقق من التحديثات المتاحة
```bash
docker exec coffee_globe_php composer outdated
```

## حل المشاكل الشائعة

### مشكلة 1: vendor directory مفقود

**الحل:**
```bash
docker exec coffee_globe_php composer install --no-dev --optimize-autoloader
```

### مشكلة 2: Class not found

**الحل:**
```bash
docker exec coffee_globe_php composer dump-autoload
```

### مشكلة 3: Extension مفقود

**الحل:**
```bash
# في Dockerfile
RUN docker-php-ext-install extension_name

# إعادة البناء
docker compose build --no-cache php
```

### مشكلة 4: Composer memory limit

**الحل:**
```bash
docker exec coffee_globe_php php -d memory_limit=-1 /usr/local/bin/composer install
```

### مشكلة 5: Package conflict

**الحل:**
```bash
# مسح الـ cache
docker exec coffee_globe_php composer clear-cache

# إعادة التثبيت
docker exec coffee_globe_php rm -rf vendor
docker exec coffee_globe_php composer install
```

## Composer Commands المفيدة

```bash
# عرض جميع الـ packages
docker exec coffee_globe_php composer show

# عرض dependency tree
docker exec coffee_globe_php composer show --tree

# البحث عن package
docker exec coffee_globe_php composer search media

# عرض معلومات package
docker exec coffee_globe_php composer info laravel/framework

# التحقق من المشاكل
docker exec coffee_globe_php composer validate

# تشخيص المشاكل
docker exec coffee_globe_php composer diagnose

# عرض الإصدار
docker exec coffee_globe_php composer --version
```

## Autoloading

### PSR-4 Autoloading
```json
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "Database\\Factories\\": "database/factories/",
        "Database\\Seeders\\": "database/seeders/"
    },
    "files": [
        "app/Helpers/helpers.php"
    ]
}
```

### إعادة بناء Autoload
```bash
# عادي
docker exec coffee_globe_php composer dump-autoload

# مُحسّن
docker exec coffee_globe_php composer dump-autoload --optimize

# مع classmap
docker exec coffee_globe_php composer dump-autoload --classmap-authoritative
```

## تحسين Composer للإنتاج

### في `SERVER_DEPLOY.sh`:
```bash
composer install \
    --no-dev \              # بدون dev dependencies
    --optimize-autoloader \ # تحسين autoloader
    --no-interaction       # بدون تفاعل
```

### Flags الشرح:
- `--no-dev`: يثبت فقط production dependencies
- `--optimize-autoloader`: يحسّن class loading (أسرع)
- `--no-interaction`: لا يطلب أي input (مهم للسكريبتات)
- `--prefer-dist`: يستخدم distribution packages (أسرع)

## مراقبة الأداء

```bash
# وقت تحميل autoloader
docker exec coffee_globe_php php -r "
  \$start = microtime(true);
  require 'vendor/autoload.php';
  echo (microtime(true) - \$start) * 1000 . ' ms';
"

# حجم vendor directory
docker exec coffee_globe_php du -sh vendor

# عدد الملفات
docker exec coffee_globe_php find vendor -type f | wc -l
```

## Best Practices

### ✅ افعل:
1. استخدم `--no-dev` في production
2. قم بـ `optimize-autoloader` في production
3. حدد versions في `composer.json`
4. التزم بـ `composer.lock` في Git
5. استخدم `composer validate` قبل الـ commit

### ❌ لا تفعل:
1. لا تحذف `composer.lock`
2. لا تعدل `vendor/` يدوياً
3. لا تستخدم `dev-master` في production
4. لا ترفع `vendor/` على Git

## ملخص العملية الآلية

```
1. Docker Build (Dockerfile)
   └─> تثبيت composer binary

2. Container Start (entrypoint.sh)
   └─> التحقق من vendor/
       ├─> إذا غير موجود → composer install
       └─> إذا موجود → تخطي

3. Deployment (SERVER_DEPLOY.sh)
   └─> composer install --no-dev --optimize-autoloader
       └─> retry على الفشل

4. Verification (check-dependencies.sh)
   └─> التحقق من جميع PHP extensions
   └─> التحقق من جميع composer packages
```

## للدعم

إذا واجهت مشاكل:
1. تشغيل: `make check-deps`
2. مراجعة: `docker logs coffee_globe_php`
3. تشخيص: `composer diagnose`
4. إعادة تثبيت: `rm -rf vendor && composer install`
