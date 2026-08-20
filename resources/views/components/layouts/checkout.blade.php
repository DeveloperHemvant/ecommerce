<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Checkout - Sonakshi Fashion Hub' }}</title>

    <!-- Google Fonts & Material Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet" />

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-warm-ivory text-charcoal-text min-h-screen flex flex-col font-body-md selection:bg-heritage-burgundy selection:text-white">
    <!-- Focused Transactional Header -->
    <header class="fixed top-0 w-full z-50 bg-surface/85 backdrop-blur-md border-b border-border-subtle transition-all duration-300 ease-in-out h-16 flex justify-between items-center px-margin-mobile md:px-margin-desktop">
        <div class="flex items-center">
            @if(isset($backUrl))
                <a href="{{ $backUrl }}" class="text-on-surface-variant hover:text-heritage-burgundy transition-colors p-1" title="Back">
                    <span class="material-symbols-outlined text-2xl">arrow_back</span>
                </a>
            @else
                <a href="{{ url('/cart') }}" class="text-on-surface-variant hover:text-heritage-burgundy transition-colors p-1" title="Back to Cart">
                    <span class="material-symbols-outlined text-2xl">arrow_back</span>
                </a>
            @endif
        </div>
        
        <a href="{{ url('/') }}" class="font-headline-md text-2xl md:text-headline-md font-semibold text-heritage-burgundy tracking-tight text-center">
            Sonakshi
        </a>

        <div class="flex items-center text-on-surface-variant text-sm font-label-caps gap-1" title="256-bit SSL Secure Checkout">
            <span class="material-symbols-outlined text-xl text-heritage-burgundy">lock</span>
            <span class="hidden sm:inline text-[11px] uppercase tracking-wider text-on-surface-variant">Secure</span>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow pt-24 pb-12 px-margin-mobile md:px-margin-desktop max-w-container-max-width mx-auto w-full">
        {{ $slot }}
    </main>

    <!-- Minimal Checkout Footer -->
    <footer class="w-full py-8 border-t border-border-subtle bg-warm-ivory flex flex-col md:flex-row justify-between items-center px-margin-mobile md:px-margin-desktop gap-4 transition-colors duration-200 mt-auto">
        <div class="font-label-caps text-xs text-heritage-burgundy">
            © {{ date('Y') }} Sonakshi Fashion Hub. Crafted with Heritage.
        </div>
        <div class="flex gap-6 font-body-md text-xs text-on-surface-variant">
            <a class="hover:text-muted-gold transition-colors" href="#">Privacy Policy</a>
            <a class="hover:text-muted-gold transition-colors" href="#">Terms of Service</a>
            <a class="hover:text-muted-gold transition-colors" href="#">Shipping Info</a>
            <a class="hover:text-muted-gold transition-colors" href="#">Help & Support</a>
        </div>
    </footer>
</body>

</html>
