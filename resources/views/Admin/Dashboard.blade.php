@extends('welcome')
@section('content')

<!-- index.blade.php or any other Blade file -->
<div class="flex h-screen bg-gray-200">
    <!-- Sidebar -->
    <div class="bg-gray-800 w-64">
        <div class="flex items-center justify-center h-16 border-b border-gray-700">
            <span class="text-white text-lg font-semibold">Sidebar</span>
        </div>
        <nav class="mt-6">
            <!-- Dashboard -->
            <a href="#" class="block py-2 px-4 text-gray-400 hover:bg-gray-700 hover:text-white">Dashboard</a>
            <!-- Projects -->
            <div class="group relative">
                <a href="#" class="block py-2 px-4 text-gray-400 hover:bg-gray-700 hover:text-white">Projects</a>
                <!-- Dropdown -->
                <div class="hidden absolute z-10 left-0 mt-2 w-48 p-2 rounded-lg shadow-lg bg-gray-700" id="dropdown">
                    <a href="#" class="block py-1 px-3 text-sm text-gray-300 hover:bg-gray-600">Project 1</a>
                    <a href="#" class="block py-1 px-3 text-sm text-gray-300 hover:bg-gray-600">Project 2</a>
                    <a href="#" class="block py-1 px-3 text-sm text-gray-300 hover:bg-gray-600">Project 3</a>
                </div>
            </div>
            <!-- Team -->
            <a href="#" class="block py-2 px-4 text-gray-400 hover:bg-gray-700 hover:text-white">Team</a>
            <!-- Settings -->
            <a href="#" class="block py-2 px-4 text-gray-400 hover:bg-gray-700 hover:text-white">Settings</a>
        </nav>
    </div>

    <!-- Content -->
    <div class="flex flex-col flex-1">
        <!-- Header -->
        <div class="bg-white shadow-md border-b border-gray-200">
            <div class="flex items-center justify-between px-6 h-16">
                <span class="text-lg font-semibold">Header</span>
            </div>
        </div>

        <!-- Main Content -->
        <div class="p-6 flex-1 overflow-y-auto">
            <p>Main content area...</p>
        </div>
    </div>
</div>

<script>
    document.getElementById('dropdown').addEventListener('mouseleave', function() {
        this.classList.add('hidden');
    });

    document.querySelector('.group').addEventListener('mouseenter', function() {
        document.getElementById('dropdown').classList.remove('hidden');
    });
</script>




@if (auth()->check())
    <p>Welcome, {{ auth()->user()->name }}</p>
@endif
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>
@endsection
