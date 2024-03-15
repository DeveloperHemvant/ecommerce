@extends('Admin.dashboard')
@section('mainsection')

<!-- index.blade.php or any other Blade file -->
<div class="flex h-screen bg-gray-200">
    <!-- Sidebar -->
   {{-- @livewire('navigation') --}}

    <!-- Content -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($categories as $category)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <img class="w-full h-56 object-cover object-center" src="{{ asset('storage/photos/' . $category->image) }}" alt="{{ $category->image }}">
            <div class="p-4">
                <h2 class="text-xl font-semibold mb-2">{{ $category->name }}</h2>
                <p class="text-gray-600">{{ $category->description }}</p>
            </div>
        </div>
        @endforeach
    </div>
    
      
</div>







@endsection

