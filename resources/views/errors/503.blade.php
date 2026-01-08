<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Yemen Coffee </title>
    <link rel="icon" type="image/webp" href="{{ asset('images/logo.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/cairo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Noto Sans", Arial, "Noto Kufi Arabic", sans-serif;
        }

        /* الخلفية */
        .hero {
            background: url('/images/banner1.webp') no-repeat center center/cover;
            height: 100%;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            text-align: center;
            color: white;
        }

        /* طبقة شفافة فوق الصورة */
        .overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.65);
        }

        /* المحتوى */
        .content {
            position: relative;
            z-index: 2;
            max-width: 700px;
            padding: 20px;
        }

        .logo {
            max-height: 200px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 32px;
            margin-bottom: 15px;
        }

        p {
            font-size: 18px;
            margin-bottom: 25px;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 8px;
            background: #22d3ee;
            color: #fff;
            font-weight: bold;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn:hover {
            background: #06b6d4;
        }

        .meta {
            margin-top: 20px;
            font-size: 14px;
            color: #d1d5db;
        }
    </style>
</head>

<body>
    <div class="hero">
        <div class="overlay"></div>
        <div class="content">
            <img src="{{ asset('images/logo.svg') }}" alt="logo" class="logo" />
            <h1>قريبًا سنعود — الموقع تحت الصيانة</h1>
            <p>نقوم بإجراء بعض التحديثات لتحسين تجربتك. نشكرك على صبرك — سنعود خلال وقت قصير.</p>
        </div>
    </div>
</body>

</html>
