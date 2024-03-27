<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6">
    @foreach($categories as $category)
    <div class="max-w-sm rounded overflow-hidden shadow-lg bg-white">
        <img class="w-full h-56 object-cover object-center" src="{{ Storage::url($category->image) }}" alt="{{ $category->image }}">
        <div class="px-6 py-4">
            <div class="font-bold text-xl mb-2">{{ $category->name }}</div>
            <p class="text-gray-600">{{ $category->description }}</p>
        </div>
        <div class="px-6 pt-4 pb-2">
            <button wire:click="update({{$category->id}})" id="update" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Update
            </button>
            <!-- Livewire Component View -->
            <a href="javascript:void(0)" 
            wire:click.prevent="deleteCategory({{ $category->id }})" 
            wire:confirm="Are you sure you want to delete this post?" 
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2"
         > Delete</a> 
        </div>
    </div>
    @endforeach
</div>



