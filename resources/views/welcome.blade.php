<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite('resources/css/app.css')
        @livewireStyles
   
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        

        <main class="">
            @yield('content')
        </main>
    </div>


    </body>
</html>
