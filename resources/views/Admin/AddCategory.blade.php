@extends('Admin.dashboard')
@section('mainsection')

<!-- index.blade.php or any other Blade file -->
<div class="flex h-screen bg-gray-200">
    <!-- Sidebar -->
   {{-- @livewire('navigation') --}}

    <!-- Content -->
    @livewire('add-category');
</div>







@endsection
