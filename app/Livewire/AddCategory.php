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
    public $photo;
    public $parentid;
  
 
    public function create(){

        $validatedData = $this->validate([
            'name' => 'required|unique:main_categories',
            'description' => 'required|max:40',
            'photo' => 'image|required',
            'parentid' => '',
        ]);
       
        MainCategory::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'image' => $this->photo->store('public/photos'),
            'parent_id' => $validatedData['parentid'],
        ]);
        $this->reset('name','description','photo');
        if ($this->parentid) {
            $this->reset('parentid');
        }
    }
    public function render()
    {
        $category=MainCategory::all();
        return view('livewire.add-category', compact('category'));
    }
}
