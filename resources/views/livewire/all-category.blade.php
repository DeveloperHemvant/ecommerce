@extends('Admin.dashboard')
@section('mainsection')
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6">
    @foreach($categories as $category)
    <div class="max-w-sm rounded overflow-hidden shadow-lg bg-white">
        <img class="w-full h-56 object-cover object-center" src="{{ Storage::url($category->image) }}" alt="{{ $category->image }}">
        <div class="px-6 py-4">
            <div class="font-bold text-xl mb-2">{{ $category->name }}</div>
            <p class="text-gray-600">{{ $category->description }}</p>
        </div>
    </div>
    @endforeach
</div>
@endsection

