<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="description" content="Kalkulator Vigenère Cipher untuk enkripsi dan dekripsi pesan dengan metode polyalphabetic cipher.">
    <title>@yield('title', 'Vigenère Cipher')</title>
    
    <!-- Bootstrap + Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Google Fonts (opsional, untuk tampilan lebih segar) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    
    <style>
        /* Custom tambahan di luar Bootstrap */
        body {
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
        }
        .card-custom {
            border: none;
            border-radius: 28px;
            backdrop-filter: blur(2px);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 30px -12px rgba(0, 0, 0, 0.2) !important;
        }
        .btn-custom {
            border-radius: 40px;
            padding: 10px 24px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-custom-primary {
            background: linear-gradient(95deg, #2c3e66, #1a2a4f);
            border: none;
            color: white;
        }
        .btn-custom-primary:hover {
            background: linear-gradient(95deg, #1e2f52, #0f1e3a);
            transform: scale(1.02);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        pre {
            background: #f8f9fc;
            border-left: 4px solid #2c3e66;
            font-size: 0.85rem;
            border-radius: 16px;
        }
        .result-text {
            font-size: 1.6rem;
            letter-spacing: 2px;
            word-break: break-word;
            background: #eef2ff;
            display: inline-block;
            padding: 6px 18px;
            border-radius: 50px;
            font-weight: 600;
        }
        footer a {
            text-decoration: none;
            color: #adb5bd;
        }
        footer a:hover {
            color: white;
        }
        .nav-link {
            font-weight: 500;
        }
    </style>
</head>