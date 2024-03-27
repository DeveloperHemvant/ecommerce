@extends('livewire.admin-dashboard')
@section('mainsection')
@livewire('edit-category', ['category' => $categoryId])
@endsection