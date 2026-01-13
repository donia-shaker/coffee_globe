#!/bin/bash

echo "=================================="
echo "Checking PHP Dependencies"
echo "=================================="
echo ""

echo "PHP Version:"
php -v | head -1
echo ""

echo "Required PHP Extensions:"
REQUIRED_EXTENSIONS=(
    "pdo"
    "pdo_mysql"
    "mbstring"
    "openssl"
    "tokenizer"
    "xml"
    "ctype"
    "json"
    "bcmath"
    "zip"
    "intl"
    "gd"
    "redis"
    "imagick"
    "curl"
    "fileinfo"
    "soap"
    "sockets"
)

MISSING_EXTENSIONS=()

for ext in "${REQUIRED_EXTENSIONS[@]}"; do
    if php -m | grep -qi "^$ext$"; then
        echo "✓ $ext"
    else
        echo "✗ $ext (MISSING)"
        MISSING_EXTENSIONS+=("$ext")
    fi
done

echo ""
echo "=================================="
echo "Checking Composer Dependencies"
echo "=================================="
echo ""

if [ ! -f "composer.json" ]; then
    echo "✗ composer.json not found!"
    exit 1
fi

echo "✓ composer.json found"

if [ ! -d "vendor" ]; then
    echo "✗ vendor directory not found - run 'composer install'"
    VENDOR_MISSING=1
else
    echo "✓ vendor directory exists"
    VENDOR_MISSING=0
fi

if [ ! -f "vendor/autoload.php" ]; then
    echo "✗ vendor/autoload.php not found"
    VENDOR_MISSING=1
else
    echo "✓ vendor/autoload.php exists"
fi

echo ""
echo "Required Composer Packages:"

REQUIRED_PACKAGES=(
    "laravel/framework"
    "donia-shaker/media-library"
    "guzzlehttp/guzzle"
    "inertiajs/inertia-laravel"
    "laravel/sanctum"
    "laravel/tinker"
    "spatie/laravel-permission"
    "tightenco/ziggy"
)

if [ -f "vendor/composer/installed.json" ]; then
    for package in "${REQUIRED_PACKAGES[@]}"; do
        if grep -q "\"name\": \"$package\"" vendor/composer/installed.json; then
            VERSION=$(grep -A 5 "\"name\": \"$package\"" vendor/composer/installed.json | grep "\"version\"" | cut -d'"' -f4 | head -1)
            echo "✓ $package ($VERSION)"
        else
            echo "✗ $package (NOT INSTALLED)"
        fi
    done
else
    echo "✗ vendor/composer/installed.json not found - run 'composer install'"
fi

echo ""
echo "=================================="
echo "Summary"
echo "=================================="

if [ ${#MISSING_EXTENSIONS[@]} -gt 0 ]; then
    echo "✗ Missing PHP Extensions: ${MISSING_EXTENSIONS[*]}"
    EXIT_CODE=1
else
    echo "✓ All required PHP extensions are installed"
    EXIT_CODE=0
fi

if [ $VENDOR_MISSING -eq 1 ]; then
    echo "✗ Composer dependencies not installed"
    echo "  Run: composer install --no-dev --optimize-autoloader"
    EXIT_CODE=1
else
    echo "✓ Composer dependencies are installed"
fi

echo ""

if [ $EXIT_CODE -eq 0 ]; then
    echo "✓ All dependencies satisfied!"
else
    echo "✗ Some dependencies are missing - please install them"
fi

exit $EXIT_CODE
