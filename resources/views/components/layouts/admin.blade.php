@props([
    'title' => 'Sonakshi Fashion Hub - Admin',
    'active' => 'dashboard'
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

<body class="bg-background text-on-background font-body-md min-h-screen flex antialiased selection:bg-heritage-burgundy selection:text-white">
    <!-- Admin Sidebar -->
    <x-admin.sidebar :active="$active" />

    <!-- Main Content Canvas -->
    <div class="flex-1 flex flex-col md:ml-64 min-h-screen">
        <!-- Mobile Admin Header -->
        <header class="md:hidden flex justify-between items-center px-4 h-16 w-full fixed top-0 bg-background/90 backdrop-blur-md border-b border-border-subtle z-30">
            <div class="flex items-center gap-3">
                <a href="{{ url('/admin') }}" class="font-headline-md text-xl font-semibold text-heritage-burgundy tracking-tight">
                    Sonakshi Admin
                </a>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" class="text-xs font-label-caps text-heritage-burgundy border border-border-subtle px-2.5 py-1 rounded">Store</a>
                <a href="{{ url('/admin/login') }}" class="text-on-surface-variant hover:text-error">
                    <span class="material-symbols-outlined text-xl">logout</span>
                </a>
            </div>
        </header>

        <!-- Page Content Slot -->
        <main class="flex-1 p-margin-mobile md:p-margin-desktop pt-20 md:pt-margin-desktop bg-background min-h-screen">
            {{ $slot }}
        </main>
    </div>
</body>

</html>
