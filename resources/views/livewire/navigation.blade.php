<div class="bg-gray-800 w-64 h-screen flex flex-col">
    <div class="flex items-center justify-center h-16 border-b border-gray-700">
        <span class="text-white text-lg font-semibold">Sidebar</span>
    </div>
    <nav class="flex-1 mt-6">
        <!-- Dashboard -->
        <a href="/admindashboard"  wire:navigate class="block py-2 px-4 text-gray-400 hover:bg-gray-700 hover:text-white">Dashboard</a>
        <!-- Projects -->
        <div class="group relative">
            <a href="#" class="block py-2 px-4 text-gray-400 hover:bg-gray-700 hover:text-white">Categories</a>
            <!-- Dropdown -->
            <div class="hidden absolute z-10 left-0 mt-2 w-48 p-2 rounded-lg shadow-lg bg-gray-700" id="dropdown">
                <a href="/addcategory"  wire:navigate class="block py-1 px-3 text-sm text-gray-300 hover:bg-gray-600">Add Category</a>
                <a href="/allcategory" wire:navigate class="block py-1 px-3 text-sm text-gray-300 hover:bg-gray-600">All Category</a>
            </div>
        </div>
        <!-- Team -->
        <a href="#" class="block py-2 px-4 text-gray-400 hover:bg-gray-700 hover:text-white">Team</a>
        <!-- Settings -->
        <a href="#" class="block py-2 px-4 text-gray-400 hover:bg-gray-700 hover:text-white">Settings</a>
    </nav>
    <div class="flex items-center justify-center h-16 border-t border-gray-700">
        @if (auth()->check())
            <p class="text-white">Welcome, {{ auth()->user()->name }}</p>
            <form method="POST" action="{{ route('logout') }}" class="ml-2">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-white">Logout</button>
            </form>
        @endif
    </div>
</div>
<script>
    document.getElementById('dropdown').addEventListener('mouseleave', function() {
        this.classList.add('hidden');
    });

    document.querySelector('.group').addEventListener('mouseenter', function() {
        document.getElementById('dropdown').classList.remove('hidden');
    });
</script>

