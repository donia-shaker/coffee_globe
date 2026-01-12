# إعداد Docker للإنتاج - Coffee Globe

## المتطلبات
- Docker 29.1.4 أو أحدث
- Docker Compose v5.0.1 أو أحدث
- Make (اختياري)

## البدء السريع

1. إعداد متغيرات البيئة في ملف `.env`:
```bash
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=coffee_globe
DB_USERNAME=coffee_globe
DB_PASSWORD=your_password
DB_ROOT_PASSWORD=root_password

APP_URL=https://coffeeglobe.sa
APP_ENV=production
APP_DEBUG=false

REDIS_HOST=redis
REDIS_PORT=6379
```

2. بناء وتشغيل الحاويات:
```bash
make build
make up
```

3. إعداد شهادات SSL:
```bash
make ssl-setup
```

## أوامر Make المتاحة

### الأوامر الأساسية
- `make build` - بناء صور Docker
- `make up` - تشغيل جميع الحاويات
- `make down` - إيقاف جميع الحاويات
- `make start` - تشغيل الحاويات المتوقفة
- `make stop` - إيقاف الحاويات قيد التشغيل
- `make restart` - إعادة تشغيل جميع الحاويات
- `make ps` - عرض حالة الحاويات
- `make status` - عرض حالة مفصلة للحاويات
- `make logs` - عرض سجلات جميع الحاويات

### الوصول إلى Shell
- `make shell-php` - فتح shell في حاوية PHP
- `make shell-nginx` - فتح shell في حاوية Nginx
- `make shell-mysql` - فتح shell في حاوية MySQL
- `make shell-redis` - فتح shell في حاوية Redis

### Composer و Artisan
- `make composer-install` - تثبيت تبعيات PHP
- `make composer-update` - تحديث تبعيات PHP
- `make artisan-migrate` - تشغيل مجريشين قاعدة البيانات
- `make artisan-seed` - تشغيل seeders قاعدة البيانات
- `make artisan-cache` - مسح جميع الـ cache
- `make artisan-optimize` - تحسين Laravel للإنتاج

### إدارة SSL
- `make ssl-setup` - إعداد شهادات SSL
- `make ssl-renew` - تجديد شهادات SSL يدوياً
- `make ssl-check` - فحص حالة شهادات SSL

### النسخ الاحتياطي والاستعادة
- `make backup` - نسخ احتياطي لقاعدة البيانات والملفات
- `make restore DB_FILE=backups/database_YYYYMMDD_HHMMSS.sql STORAGE_FILE=backups/storage_YYYYMMDD_HHMMSS.tar.gz` - استعادة من النسخ الاحتياطي

### الصيانة
- `make pull` - سحب أحدث الصور
- `make rebuild` - إعادة بناء وإعادة تشغيل الحاويات
- `make clean` - إزالة الحاويات والمجلدات

## تخزين الملفات

ملفات الوسائط مخزنة في `server_storage/media` على الخادم المضيف وليس داخل الحاويات. هذا يضمن:
- استمرار الملفات بعد إعادة تشغيل الحاويات
- سهولة إدارة الملفات والنسخ الاحتياطي
- الوصول المباشر للملفات من الخادم

جميع مجلدات التخزين لديها صلاحيات مناسبة (775) يتم تعيينها تلقائياً:
- `storage/` - تخزين Laravel
- `server_storage/media/` - ملفات الوسائط
- `bootstrap/cache/` - ملفات الـ cache
- السجلات في volumes للوصول السهل

## إعداد Bind Mounts

### مجلدات Nginx
- كود التطبيق: للقراءة فقط
- التخزين: للقراءة فقط (خدمة الملفات)
- تخزين الخادم: للقراءة فقط (خدمة الوسائط)
- السجلات: للقراءة والكتابة (ملفات السجلات)
- شهادات SSL: للقراءة فقط

### مجلدات PHP
- كود التطبيق: للقراءة والكتابة
- مجلدات التخزين: للقراءة والكتابة (للرفع والـ cache والسجلات)
- تخزين الخادم: للقراءة والكتابة (لرفع الوسائط)
- ملفات التكوين: للقراءة فقط
- volume السجلات: للقراءة والكتابة

### قاعدة البيانات و Redis
- volumes البيانات: تخزين دائم
- volumes السجلات: لملفات السجلات

## مجريشين قاعدة البيانات

المجريشين تعمل تلقائياً عند بدء الحاوية. سكربت entrypoint:
- ينتظر جاهزية MySQL
- يشغل المجريشين
- يشغل seeders (مع التحقق من التكرار)
- ينشئ رابط تخزين
- يخزن التكوين في الـ cache

## شهادات SSL

شهادات SSL يتم إدارتها عبر certbot وتخزن في `docker/nginx/ssl/`. 

### الإعداد الأولي
سكربت الإعداد (`make ssl-setup`):
- يتحقق من وجود الشهادات
- يتحقق من انتهاء الصلاحية (يجدد إذا كانت أقل من 30 يوم)
- يحصل على شهادات جديدة إذا لزم الأمر
- يعدل nginx تلقائياً

### التجديد التلقائي
التجديد التلقائي للشهادات يتم عبر:
1. **Cron Job**: يعمل يومياً الساعة 3 صباحاً داخل حاوية Nginx
   - يفحص الشهادات تلقائياً
   - يجددها عند الحاجة (قبل 30 يوم من انتهاء الصلاحية)
   - السجلات في `/var/log/cron/certbot-renew.log`

2. **Renewal Hooks**: بعد كل تجديد تلقائي:
   - يتم نسخ الشهادات المحدثة إلى `docker/nginx/ssl/`
   - يتم إعادة تحميل Nginx تلقائياً
   - يتم تسجيل العملية في السجلات

### التجديد اليدوي
يمكنك تجديد الشهادات يدوياً:
```bash
make ssl-renew
```

أو فحص حالة الشهادات:
```bash
make ssl-check
```

### التخزين
- ملفات الشهادات يتم ربطها من المضيف إلى الحاوية للاستمرارية
- شهادات Let's Encrypt محفوظة في Docker volumes (`letsencrypt` و `letsencrypt-archive`)
- النسخ المحدثة تُحفظ في `docker/nginx/ssl/` على الخادم المضيف

## تحسينات الأداء

### Nginx
- FastCGI caching مفعل
- ضغط Gzip
- تخزين الملفات
- أحجام buffers محسنة
- دعم HTTP/2

### PHP-FPM
- إدارة ديناميكية للعمليات (10-50 worker)
- OPcache مفعل
- زيادة حدود الذاكرة
- timeouts محسنة

### MySQL
- buffer pool محسن
- query cache مفعل
- connection pooling

## النشر للإنتاج

1. تأكد من أن DNS النطاق يشير إلى الخادم
2. حدد متغيرات البيئة في `.env`
3. شغل `make build && make up`
4. شغل `make ssl-setup`
5. تحقق من التطبيق على https://coffeeglobe.sa
6. راقب بـ `make status`

## تكوين Nginx

ملف تكوين nginx اسمه `coffeeglobe.sa.conf` لتسهيل التعرف والتعديل. يتضمن:
- كتل خادم HTTP و HTTPS
- FastCGI caching
- رؤوس الأمان
- خدمة ملفات ثابتة محسنة
- aliases للوسائط والتخزين

## استكشاف الأخطاء

### مشاكل الصلاحيات
جميع الصلاحيات يتم تعيينها تلقائياً في Dockerfile وسكربت entrypoint. إذا واجهت مشاكل:
```bash
make shell-php
chmod -R 775 storage bootstrap/cache server_storage
```

### عرض السجلات
```bash
make logs
# أو خدمة محددة
docker logs coffee_globe_php
docker logs coffee_globe_nginx
# سجلات تجديد SSL
docker exec coffee_globe_nginx cat /var/log/cron/certbot-renew.log
```

### التحقق من الحالة
```bash
make status
```

### فحص شهادات SSL
```bash
make ssl-check
# أو يدوياً
docker exec coffee_globe_nginx certbot certificates
```

### مشاكل تجديد SSL
إذا لم يتم تجديد الشهادات تلقائياً:
```bash
# فحص سجلات cron
docker exec coffee_globe_nginx cat /var/log/cron/certbot-renew.log
# تجديد يدوي
make ssl-renew
```

### إعادة بناء كل شيء
```bash
make rebuild
```
