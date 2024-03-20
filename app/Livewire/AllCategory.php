<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MainCategory; 
class AllCategory extends Component
{
    public function deleteConfirmation($id)
    {
        
        dd("hello");
    }
    public function render()
    {
        $categories=MainCategory::all();
        return view('livewire.all-category', compact('categories'));
    }
}
