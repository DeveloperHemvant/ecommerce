<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css
" rel="stylesheet">
        <!-- Styles -->
        @vite('resources/css/app.css')
        @livewireStyles
   
</head>
<body class="font-sans antialiased w-full bg-gradient-to-r from-red-400 via-yellow-400 to-green-400">
    <div class="min-h-screen bg-gray-100">
            @yield('content')
    </div>
@livewireScripts

<script>
    document.getElementById("update").addEventListener("click", myFunction);

function myFunction() {
    Swal.fire({
  title: "Are you sure?",
  text: "You won't be able to revert this!",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "Yes, delete it!"
}).then((result) => {
  if (result.isConfirmed) {
    // Swal.fire({
    //   title: "Deleted!",
    //   text: "Your file has been deleted.",
    //   icon: "success"
    // });
    
  }
});
}
</script>
    </body>
</html>
