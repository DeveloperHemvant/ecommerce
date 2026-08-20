@props([
    'title' => 'Sign In - Sonakshi Fashion Hub',
    'subtitle' => 'Crafted with Heritage',
    'isAdmin' => false
])

<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title }}</title>

    <!-- Google Fonts & Material Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet" />

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-warm-ivory text-charcoal-text min-h-screen flex flex-col justify-between font-body-md selection:bg-heritage-burgundy selection:text-white">
    <!-- Header with Back & Home link -->
    <header class="w-full px-6 md:px-12 py-6 flex justify-between items-center">
        <a href="{{ url('/') }}" class="font-headline-md text-2xl font-semibold text-heritage-burgundy tracking-tight hover:opacity-90 transition-opacity">
            Sonakshi
        </a>
        <a href="{{ url('/') }}" class="text-xs font-label-caps uppercase text-on-surface-variant hover:text-heritage-burgundy transition-colors flex items-center gap-1">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            Back to Store
        </a>
    </header>

    <!-- Auth Card Slot -->
    <main class="flex-grow flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-md bg-surface-container-lowest border border-border-subtle rounded-2xl p-8 md:p-10 shadow-[0px_10px_30px_rgba(96,0,24,0.05)]">
            <div class="text-center mb-8">
                @if($isAdmin)
                    <div class="w-12 h-12 rounded-full bg-cream-silk border border-muted-gold/30 text-heritage-burgundy flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-2xl">admin_panel_settings</span>
                    </div>
                @endif
                <h1 class="font-headline-md text-2xl md:text-3xl text-heritage-burgundy font-semibold mb-2">
                    {{ $title }}
                </h1>
                <p class="font-body-md text-sm text-on-surface-variant">
                    {{ $subtitle }}
                </p>
            </div>

            {{ $slot }}
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full py-6 text-center text-xs font-body-md text-on-surface-variant/70 border-t border-border-subtle/50">
        © {{ date('Y') }} Sonakshi Fashion Hub. All rights reserved.
    </footer>
</body>

</html>
