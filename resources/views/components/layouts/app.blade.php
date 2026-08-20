<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
    <title>{{ $title ?? 'Sonakshi Fashion Hub - Royal Ethnic Couture' }}</title>

    <!-- Progressive Web App (PWA) Meta Tags -->
    <link rel="manifest" href="/manifest.json" />
    <meta name="theme-color" content="#600018" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="apple-mobile-web-app-title" content="Sonakshi" />
    <link rel="apple-touch-icon" href="/icons/apple-touch-icon.png" />
    <link rel="apple-touch-icon" sizes="152x152" href="/icons/icon-152x152.png" />
    <link rel="apple-touch-icon" sizes="192x192" href="/icons/icon-192x192.png" />
    <link rel="icon" type="image/svg+xml" href="/icons/icon.svg" />
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/icon-72x72.png" />

    <!-- Google Fonts & Material Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet" />

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-warm-ivory text-on-background antialiased font-body-md overflow-x-hidden min-h-screen selection:bg-heritage-burgundy selection:text-white">
    <!-- Top Navigation Bar -->
    <x-header />

    <!-- Main Content -->
    <main class="pt-24 pb-32 md:pt-32 md:pb-16 px-margin-mobile md:px-margin-desktop max-w-[1440px] mx-auto">
        {{ $slot }}
    </main>

    <!-- Floating YouTube Button -->
    <x-floating-action-btn />

    <!-- Mobile Bottom Navigation Bar -->
    <x-bottom-nav />

    <!-- PWA Install Banner -->
    <x-pwa-install-banner />

    <!-- Footer -->
    <x-footer />
</body>

</html>
