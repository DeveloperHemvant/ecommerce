@props([
    'active' => null
])

@php
    $current = $active ?? (request()->is('collections*') ? 'shop' : (request()->is('cart*') ? 'cart' : (request()->is('login*') || request()->is('register*') ? 'account' : (request()->is('/') ? 'home' : 'shop'))));

    $bottomCart = session()->get('cart', []);
    $bottomCartCount = array_sum(array_column($bottomCart, 'quantity'));
@endphp

<nav
    class="fixed bottom-0 left-0 w-full flex justify-around items-center h-16 pb-safe px-2 z-50 md:hidden bg-warm-ivory border-t border-border-subtle shadow-[0px_-2px_10px_rgba(96,0,24,0.05)] backdrop-blur-md bg-warm-ivory/95">
    <a class="flex flex-col items-center justify-center {{ $current === 'home' ? 'text-heritage-burgundy bg-surface-container-highest/50 rounded-xl px-2 py-1' : 'text-on-surface-variant hover:text-heritage-burgundy' }} transition-colors w-14"
        href="{{ url('/') }}">
        <span class="material-symbols-outlined text-xl mb-1" @if($current === 'home') data-weight="fill" style="font-variation-settings: 'FILL' 1;" @endif>home</span>
        <span class="font-label-caps text-[10px]">Home</span>
    </a>
    
    <a class="flex flex-col items-center justify-center {{ $current === 'shop' ? 'text-heritage-burgundy bg-surface-container-highest/50 rounded-xl px-2 py-1' : 'text-on-surface-variant hover:text-heritage-burgundy' }} transition-colors w-14"
        href="{{ url('/collections') }}">
        <span class="material-symbols-outlined text-xl mb-1" @if($current === 'shop') data-weight="fill" style="font-variation-settings: 'FILL' 1;" @endif>storefront</span>
        <span class="font-label-caps text-[10px]">Shop</span>
    </a>
    
    <a class="flex flex-col items-center justify-center {{ $current === 'videos' ? 'text-heritage-burgundy bg-surface-container-highest/50 rounded-xl px-2 py-1' : 'text-on-surface-variant hover:text-heritage-burgundy' }} transition-colors w-14"
        href="{{ url('/') }}#lookbooks">
        <span class="material-symbols-outlined text-xl mb-1" @if($current === 'videos') data-weight="fill" style="font-variation-settings: 'FILL' 1;" @endif>play_circle</span>
        <span class="font-label-caps text-[10px]">Videos</span>
    </a>
    
    <a class="flex flex-col items-center justify-center {{ $current === 'cart' ? 'text-heritage-burgundy bg-surface-container-highest/50 rounded-xl px-2 py-1' : 'text-on-surface-variant hover:text-heritage-burgundy' }} transition-colors w-14 relative"
        href="{{ url('/cart') }}">
        <span class="material-symbols-outlined text-xl mb-1" @if($current === 'cart') data-weight="fill" style="font-variation-settings: 'FILL' 1;" @endif>shopping_bag</span>
        <span class="font-label-caps text-[10px]">Cart</span>
        @if($bottomCartCount > 0)
            <span class="absolute top-1 right-2 bg-heritage-burgundy text-white text-[9px] w-3.5 h-3.5 rounded-full flex items-center justify-center font-bold">{{ $bottomCartCount }}</span>
        @endif
    </a>
    
    <a class="flex flex-col items-center justify-center {{ $current === 'account' ? 'text-heritage-burgundy bg-surface-container-highest/50 rounded-xl px-2 py-1' : 'text-on-surface-variant hover:text-heritage-burgundy' }} transition-colors w-14"
        href="{{ Auth::check() ? route('account.profile') : route('login') }}">
        <span class="material-symbols-outlined text-xl mb-1" @if($current === 'account') data-weight="fill" style="font-variation-settings: 'FILL' 1;" @endif>person</span>
        <span class="font-label-caps text-[10px]">Account</span>
    </a>
</nav>
