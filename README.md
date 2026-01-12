# Coffee Globe - دليل الإعداد والتشغيل

## نظرة عامة

Coffee Globe هو تطبيق Laravel مبني باستخدام Inertia.js و Vue.js. هذا الدليل يشرح كيفية إعداد وتشغيل التطبيق في بيئة الإنتاج باستخدام Docker.

## المتطلبات الأساسية

قبل البدء، تأكد من تثبيت:
- Docker 29.1.4 أو أحدث
- Docker Compose v5.0.1 أو أحدث
- Make (اختياري لكن موصى به)

للتحقق من الإصدارات:
```bash
docker --version
docker compose version
```

## البدء السريع

### الخطوة 1: إعداد ملف البيئة

انسخ ملف `.env.example` إلى `.env`:
```bash
cp .env.example .env
```

عدل ملف `.env` وأضف القيم المطلوبة:
```bash
APP_NAME="Coffee Globe"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://coffeeglobe.sa

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=coffee_globe
DB_USERNAME=coffee_globe_user
DB_PASSWORD=your_strong_password
DB_ROOT_PASSWORD=your_root_password

REDIS_HOST=redis
REDIS_PORT=6379
```

### الخطوة 2: توليد مفتاح التطبيق

```bash
make shell-php
php artisan key:generate
exit
```

أو بدون Make:
```bash
docker compose exec php php artisan key:generate
```

### الخطوة 3: بناء وتشغيل الحاويات

```bash
make build
make up
```

هذا الأمر سيقوم بـ:
- بناء صور Docker لجميع الخدمات
- تشغيل الحاويات في الخلفية
- انتظار جاهزية الخدمات
- تشغيل المجريشين تلقائياً
- تحسين التطبيق للإنتاج

### الخطوة 4: إعداد شهادات SSL

```bash
make ssl-setup
```

هذا الأمر يحتاج إلى:
- أن يكون النطاق يشير إلى الخادم
- فتح المنفذ 80 و 443

## شرح الأوامر بالتفصيل

### أوامر البناء والتشغيل

#### `make build`
يبني صور Docker من الصفر بدون استخدام الـ cache. استخدمه عند:
- تغيير Dockerfile
- تحديث التبعيات
- تغيير إعدادات الحاويات

#### `make up`
يشغل جميع الحاويات ويقوم بـ:
- انتظار 10 ثواني لبدء الخدمات
- تشغيل المجريشين تلقائياً
- تحسين التطبيق (config cache, route cache, view cache)

#### `make down`
يوقف جميع الحاويات ويحذف الشبكات. لا يحذف البيانات المخزنة في volumes.

#### `make start`
يشغل الحاويات المتوقفة فقط. استخدمه بعد `make stop`.

#### `make stop`
يوقف الحاويات بدون حذفها. البيانات تبقى محفوظة.

#### `make restart`
يعيد تشغيل جميع الحاويات. مفيد بعد تغيير إعدادات.

### أوامر المراقبة

#### `make ps`
يعرض قائمة سريعة بجميع الحاويات وحالتها.

#### `make status`
يعرض تقرير مفصل يشمل:
- حالة جميع الحاويات
- حالة PHP-FPM
- حالة Nginx
- حالة MySQL
- حالة Redis

#### `make logs`
يعرض سجلات جميع الحاويات بشكل مباشر. للخروج اضغط Ctrl+C.

### أوامر الوصول

#### `make shell-php`
يفتح shell داخل حاوية PHP. استخدمه لـ:
- تشغيل أوامر artisan
- تثبيت packages جديدة
- فحص الملفات
- تصحيح الأخطاء

#### `make shell-nginx`
يفتح shell داخل حاوية Nginx. مفيد لفحص إعدادات nginx.

#### `make shell-mysql`
يفتح shell داخل حاوية MySQL. استخدمه للوصول المباشر لقاعدة البيانات:
```bash
mysql -u coffee_globe_user -p coffee_globe
```

#### `make shell-redis`
يفتح shell داخل حاوية Redis. للوصول إلى Redis CLI:
```bash
redis-cli
```

### أوامر Composer

#### `make composer-install`
يثبت جميع تبعيات PHP المطلوبة للإنتاج بدون dev dependencies.

#### `make composer-update`
يحدث جميع تبعيات PHP إلى أحدث إصدار متوافق.

### أوامر Artisan

#### `make artisan-migrate`
يشغل جميع المجريشين الجديدة. يتم تشغيلها تلقائياً عند `make up` لكن يمكنك تشغيلها يدوياً.

#### `make artisan-seed`
يشغل جميع seeders. Seeders تتحقق من البيانات الموجودة قبل الإضافة.

#### `make artisan-cache`
يمسح جميع أنواع الـ cache:
- config cache
- route cache
- view cache
- application cache

#### `make artisan-optimize`
يحسن أداء التطبيق عبر:
- تخزين config في cache
- تخزين routes في cache
- تخزين views في cache

### أوامر SSL

#### `make ssl-setup`
يقوم بـ:
- التحقق من وجود شهادات SSL
- التحقق من انتهاء الصلاحية
- الحصول على شهادات جديدة إذا لزم الأمر
- إعادة تحميل nginx

#### `make ssl-renew`
يجدد شهادات SSL الموجودة. استخدمه يدوياً أو ضعه في cron job.

### أوامر النسخ الاحتياطي

#### `make backup`
ينشئ نسخة احتياطية من:
- قاعدة البيانات (جميع قواعد البيانات)
- ملفات التخزين (storage و server_storage)

النسخ الاحتياطية تُحفظ في مجلد `backups/` مع timestamp.

#### `make restore`
يستعيد من النسخ الاحتياطي:
```bash
make restore DB_FILE=backups/database_20240101_120000.sql STORAGE_FILE=backups/storage_20240101_120000.tar.gz
```

### أوامر الصيانة

#### `make pull`
يسحب أحدث صور Docker من Docker Hub. استخدمه قبل `make rebuild`.

#### `make rebuild`
يقوم بـ:
- إيقاف الحاويات
- إعادة بناء الصور من الصفر
- تشغيل الحاويات
- تشغيل المجريشين
- تحسين التطبيق

#### `make clean`
يحذف جميع الحاويات والمجلدات والشبكات. احذر: هذا يحذف جميع البيانات.

## هيكل المشروع

```
coffee_globe/
├── app/                    # كود التطبيق
├── config/                 # ملفات التكوين
├── database/               # المجريشين والـ seeders
├── docker/                  # ملفات Docker
│   ├── nginx/              # إعدادات Nginx
│   │   ├── conf.d/         # ملفات التكوين
│   │   │   └── coffeeglobe.sa.conf
│   │   └── nginx.conf      # الإعدادات الرئيسية
│   ├── php/                # إعدادات PHP
│   │   ├── php.ini
│   │   ├── php-fpm.conf
│   │   └── opcache.ini
│   ├── mysql/              # إعدادات MySQL
│   ├── ssl/                # سكربتات SSL
│   └── entrypoint.sh       # سكربت بدء التشغيل
├── public/                 # الملفات العامة
├── resources/              # الموارد (views, js, css)
├── routes/                 # ملفات المسارات
├── server_storage/         # تخزين الملفات على الخادم
│   └── media/             # ملفات الوسائط
├── storage/                # تخزين Laravel
├── Dockerfile              # صورة PHP
├── docker-compose.yml      # تكوين Docker Compose
└── Makefile                # أوامر Make
```

## إعدادات الحاويات

### MySQL
- اسم الخدمة: `mysql`
- المنفذ: `3306`
- البيانات: محفوظة في volume `mysql_data`
- السجلات: في volume `mysql_logs`

### Redis
- اسم الخدمة: `redis`
- المنفذ: `6379`
- البيانات: محفوظة في volume `redis_data`
- السجلات: في volume `redis_logs`

### PHP
- اسم الخدمة: `php`
- يعمل على PHP-FPM
- متصل بـ MySQL و Redis
- جميع مجلدات التخزين قابلة للكتابة

### Nginx
- اسم الخدمة: `nginx`
- المنافذ: `80` و `443`
- يخدم الملفات الثابتة
- يمرر طلبات PHP إلى حاوية PHP

## إدارة الملفات

### رفع الملفات
الملفات المرفوعة تُحفظ في `server_storage/media/` على الخادم المضيف. هذا يعني:
- الملفات لا تُحذف عند إعادة تشغيل الحاويات
- يمكنك الوصول للملفات مباشرة من الخادم
- سهولة النسخ الاحتياطي

### الصلاحيات
جميع الصلاحيات يتم تعيينها تلقائياً:
- `storage/`: 775
- `bootstrap/cache/`: 775
- `server_storage/`: 775

## استكشاف الأخطاء

### الحاويات لا تبدأ
```bash
make logs
```
افحص السجلات لمعرفة المشكلة.

### مشاكل قاعدة البيانات
```bash
make shell-mysql
mysql -u root -p
```

### مشاكل Redis
```bash
make shell-redis
redis-cli ping
```

### مشاكل الصلاحيات
```bash
make shell-php
chmod -R 775 storage bootstrap/cache server_storage
chown -R www-data:www-data storage bootstrap/cache server_storage
```

### إعادة بناء كاملة
```bash
make clean
make build
make up
```

## النشر للإنتاج

### قبل النشر
1. تأكد من أن DNS يشير إلى الخادم
2. عدل جميع كلمات المرور في `.env`
3. توليد `APP_KEY`
4. إعداد SMTP للبريد الإلكتروني
5. إعداد SSL

### خطوات النشر
```bash
git pull origin main
make pull
make rebuild
make ssl-setup
make status
```

### المراقبة
```bash
make status    # حالة الخدمات
make logs      # السجلات
```

## الأمان

- جميع كلمات المرور يجب أن تكون قوية (32+ حرف)
- `APP_DEBUG=false` في الإنتاج
- `SESSION_SECURE_COOKIE=true` للإنتاج
- `SESSION_ENCRYPT=true` للإنتاج
- استخدم HTTPS فقط في الإنتاج
- راجع كلمات المرور كل 90 يوم

## الدعم

للمساعدة أو الإبلاغ عن مشاكل، راجع السجلات:
```bash
make logs
docker logs coffee_globe_php
docker logs coffee_globe_nginx
```
