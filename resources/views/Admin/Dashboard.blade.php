@extends('welcome')
@section('content')

<!-- index.blade.php or any other Blade file -->
<div class="flex h-screen bg-gray-200">
    <!-- Sidebar -->
   @livewire('navigation')

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
