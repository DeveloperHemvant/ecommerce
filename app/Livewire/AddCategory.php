<?php

namespace App\Livewire;
use App\Models\MainCategory; 
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddCategory extends Component
{
    use WithFileUploads;

    public $name;
    public $description;
    public $image;
    public $parentid;
  
    public function create(){
        $validatedData = $this->validate([
            'name' => 'required|unique:main_categories',
            'description' => 'required|max:40',
            'image' => 'image|required',
            'parentid' => '',
        ]);

        
      
        $filename = md5($this->image . microtime()).'.'.$this->image->extension();

        $this->image->store(path: 'images');
        MainCategory::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'image' => $filename, 
            'parent_id' => $validatedData['parentid'],
        ]);

        // Reset input fields after successful submission
        $this->reset();
        
    }
    public function render()
    {
        $category=MainCategory::all();
        return view('livewire.add-category', compact('category'));
    }
}
