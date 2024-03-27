<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MainCategory; 
class AllCategory extends Component
{
    public function deleteCategory($id)
    {
        
        $category = MainCategory::find($id);
        if ($category) {
            $category->delete();
        }
    }
    public function update($categoryId){
        
        return redirect()->route('EditCategory', ['category' => $categoryId]);
    }
    public function render()
    {
        $categories=MainCategory::all();
        return view('livewire.all-category', compact('categories'));
    }
}
