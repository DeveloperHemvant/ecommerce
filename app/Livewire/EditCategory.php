<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MainCategory; 

class EditCategory extends Component
{
    public $category;

    public function mount(MainCategory $category)
    {
        $this->category = $category;
    }
    public function render($categoryId)
    {
        return view('livewire.edit-category');
    }
}
