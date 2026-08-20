@php
    $cart = session()->get('cart', []);
    $cartCount = array_sum(array_column($cart, 'quantity'));

    $wishlistCount = 0;
    if (Auth::check()) {
        $wishlistCount = Auth::user()->wishlists()->count();
    } else {
        $wishlistCount = count(session('wishlist', []));
    }
@endphp

<header
    class="fixed top-0 w-full z-50 bg-background/85 backdrop-blur-md border-b border-border-subtle flex justify-between items-center px-margin-mobile md:px-margin-desktop h-16 transition-opacity opacity-100">
    <div class="flex items-center gap-6 text-heritage-burgundy">
        <a href="{{ route('home') }}" class="font-headline-md text-2xl md:text-headline-md font-semibold text-heritage-burgundy tracking-tight hover:opacity-90 transition-opacity">
            Sonakshi
        </a>
        <nav class="hidden md:flex items-center gap-6 ml-4">
            <a href="{{ route('collections') }}" class="font-label-caps text-xs text-on-surface-variant hover:text-heritage-burgundy transition-colors tracking-widest uppercase {{ request()->is('collections*') ? 'text-heritage-burgundy font-bold' : '' }}">
                Collections
            </a>
            <a href="{{ route('track.order') }}" class="font-label-caps text-xs text-on-surface-variant hover:text-heritage-burgundy transition-colors tracking-widest uppercase {{ request()->is('track-order*') ? 'text-heritage-burgundy font-bold' : '' }}">
                Track Order
            </a>
            @if(Auth::check() && Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="font-label-caps text-xs text-muted-gold hover:text-heritage-burgundy transition-colors tracking-widest uppercase">
                    Admin Panel
                </a>
            @endif
        </nav>
    </div>
    
    <div class="flex items-center gap-4">
        <!-- Wishlist -->
        <a href="{{ route('wishlist') }}"
            class="text-on-surface-variant hover:text-heritage-burgundy transition-colors duration-300 font-label-caps text-xs uppercase tracking-widest flex items-center gap-1 relative group"
            title="My Wishlist">
            <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">favorite</span>
            @if($wishlistCount > 0)
                <span class="bg-muted-gold text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold font-data-tabular">
                    {{ $wishlistCount }}
                </span>
            @endif
        </a>

        <!-- Customer Account / Orders -->
        @auth
            <div class="flex items-center gap-2">
                <a href="{{ route('account.orders') }}"
                    class="text-on-surface-variant hover:text-heritage-burgundy transition-colors font-label-caps text-xs uppercase tracking-widest flex items-center gap-1.5"
                    title="My Orders">
                    <span class="material-symbols-outlined text-xl">account_circle</span>
                    <span class="hidden sm:inline font-bold">{{ Auth::user()->name }}</span>
                </a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-on-surface-variant hover:text-error transition-colors p-1 cursor-pointer" title="Sign Out">
                        <span class="material-symbols-outlined text-base">logout</span>
                    </button>
                </form>
            </div>
        @else
            <a href="{{ route('login') }}"
                class="text-on-surface-variant hover:text-heritage-burgundy transition-colors duration-300 font-label-caps text-xs uppercase tracking-widest flex items-center gap-1.5"
                title="Customer Account">
                <span class="material-symbols-outlined text-xl">person</span>
                <span class="hidden md:inline">Sign In</span>
            </a>
        @endauth

        <!-- Cart / Bag -->
        <a href="{{ route('cart') }}"
            class="text-on-surface-variant hover:text-heritage-burgundy transition-colors duration-300 font-label-caps text-xs uppercase tracking-widest flex items-center gap-1.5 relative group"
            title="Shopping Cart">
            <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">shopping_bag</span>
            <span class="hidden md:inline">Bag</span>
            @if($cartCount > 0)
                <span class="bg-heritage-burgundy text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold font-data-tabular">
                    {{ $cartCount }}
                </span>
            @endif
        </a>
    </div>
</header>
