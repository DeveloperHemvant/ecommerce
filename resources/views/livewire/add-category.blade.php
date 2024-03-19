
<div class="flex flex-col flex-1">
    
    <!-- Main Content -->
     <div class="p-6 flex-1 overflow-y-auto">
        <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8">
                <div>
                    <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                        Add Category
                    </h2>
                </div>
                <form wire:submit.prevent="create" class="mt-8 space-y-6"  method="POST" enctype="multipart/form-data">
                    
                    <div class="rounded-md shadow-sm -space-y-px">
                        <div>
                            <label for="name" class="sr-only">Name</label>
                            <input wire:model='name' id="name" name="name" type="text" required
                                   class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                                   placeholder="Name">
                            @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="description" class="sr-only">Description</label>
                            <textarea wire:model='description' id="description" name="description" rows="3" required
                                      class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                                      placeholder="Description"></textarea>
                            @error('description') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="image" class="sr-only">Image</label>
                            <input wire:model.blur='photo' id="photo" name="photo" type="file" required
                                   class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                                   placeholder="Image">
                                   @if ($photo)
                                   Photo Preview:
                                   <img src="{{ $photo->temporaryUrl() }}">
                               @endif
                            @error('image') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="parent_category" class="sr-only">Parent Category</label>
                            <select wire:model='parentid' id="parent_category" name="parent_category" 
                                    class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                                <option value="">Select Parent Category</option>
                                @foreach ($category as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                                <!-- Option values to be populated dynamically -->
                            </select>
                            @error('parentid') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                                class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Sign up
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('clearErrorMessage', function (inputId) {
            setTimeout(function () {
                var errorMessageElement = document.getElementById(inputId + '-error');
                console.log(errorMessageElement);
                if (errorMessageElement) {
                    errorMessageElement.textContent = '';
                }
            }, 3000);
        });
    });
</script> 
