<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Sonakshi Fashion Hub - YouTube Shopping' }}</title>

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

    <!-- Footer -->
    <x-footer />
</body>

</html>
