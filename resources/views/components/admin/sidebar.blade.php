@props([
    'active' => 'dashboard'
])

<aside class="hidden md:flex flex-col h-screen py-6 w-64 border-r border-border-subtle bg-surface-container-low fixed left-0 top-0 z-40">
    <!-- Brand & Admin Profile Header -->
    <div class="px-6 mb-8 flex items-center gap-3">
        <div class="w-11 h-11 rounded-full overflow-hidden border border-border-subtle shrink-0">
            <img class="w-full h-full object-cover"
                data-alt="Platform Manager Avatar"
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuATRNinkluNIc-RW4nWkCBoIABaZoaVufNIxl-o-MHYjBSX3Mi_XNAXyAdPr1kLAY6dEJs4C2OVouJjFkmr1kSO8XLTD_0_gSKLC9AOS_wuPB-aQrF9tqn2PGBRsHdNRf2vYAmb0AMHhzfRQ6fhzGW_jkL6tEwmVV4fXs31xkfg00AHTDC_w0sHJ1j8gqLvgRcNSb-p1MrAJsKUtnISZRZA7NLyPy2cA8cQvExmHVc9OxqbzLVal6aU" />
        </div>
        <div>
            <h2 class="font-title-lg text-base font-semibold text-heritage-burgundy leading-tight">
                {{ auth()->user()->name ?? 'Sonakshi Admin' }}
            </h2>
            <p class="font-body-md text-xs text-on-surface-variant">Platform Manager</p>
        </div>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 overflow-y-auto px-3 space-y-1.5 font-body-md text-sm">
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ $active === 'dashboard' ? 'bg-primary text-on-primary font-medium translate-x-1 shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-heritage-burgundy' }}"
            href="{{ route('admin.dashboard') }}">
            <span class="material-symbols-outlined text-xl" @if($active === 'dashboard') data-weight="fill" style="font-variation-settings: 'FILL' 1;" @endif>dashboard</span>
            <span>Dashboard</span>
        </a>

        <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ $active === 'categories' ? 'bg-primary text-on-primary font-medium translate-x-1 shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-heritage-burgundy' }}"
            href="{{ route('admin.categories.index') }}">
            <span class="material-symbols-outlined text-xl" @if($active === 'categories') data-weight="fill" style="font-variation-settings: 'FILL' 1;" @endif>category</span>
            <span>Categories</span>
        </a>

        <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ $active === 'products' ? 'bg-primary text-on-primary font-medium translate-x-1 shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-heritage-burgundy' }}"
            href="{{ route('admin.products.index') }}">
            <span class="material-symbols-outlined text-xl" @if($active === 'products') data-weight="fill" style="font-variation-settings: 'FILL' 1;" @endif>dry_cleaning</span>
            <span>Products &amp; Stock</span>
        </a>

        <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ $active === 'youtube' ? 'bg-primary text-on-primary font-medium translate-x-1 shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-heritage-burgundy' }}"
            href="{{ route('admin.youtube.index') }}">
            <span class="material-symbols-outlined text-xl" @if($active === 'youtube') data-weight="fill" style="font-variation-settings: 'FILL' 1;" @endif>smart_display</span>
            <span>YouTube CMS</span>
        </a>

        <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ $active === 'orders' ? 'bg-primary text-on-primary font-medium translate-x-1 shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-heritage-burgundy' }}"
            href="{{ route('admin.orders.index') }}">
            <span class="material-symbols-outlined text-xl" @if($active === 'orders') data-weight="fill" style="font-variation-settings: 'FILL' 1;" @endif>package_2</span>
            <span>Orders</span>
        </a>

        <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ $active === 'coupons' ? 'bg-primary text-on-primary font-medium translate-x-1 shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-heritage-burgundy' }}"
            href="{{ route('admin.coupons.index') }}">
            <span class="material-symbols-outlined text-xl" @if($active === 'coupons') data-weight="fill" style="font-variation-settings: 'FILL' 1;" @endif>confirmation_number</span>
            <span>Coupons</span>
        </a>

        <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ $active === 'reviews' ? 'bg-primary text-on-primary font-medium translate-x-1 shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-heritage-burgundy' }}"
            href="{{ route('admin.reviews.index') }}">
            <span class="material-symbols-outlined text-xl" @if($active === 'reviews') data-weight="fill" style="font-variation-settings: 'FILL' 1;" @endif>rate_review</span>
            <span>Reviews</span>
        </a>

        <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ $active === 'customers' ? 'bg-primary text-on-primary font-medium translate-x-1 shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-heritage-burgundy' }}"
            href="{{ route('admin.customers.index') }}">
            <span class="material-symbols-outlined text-xl" @if($active === 'customers') data-weight="fill" style="font-variation-settings: 'FILL' 1;" @endif>group</span>
            <span>Customers</span>
        </a>

        <div class="pt-4 border-t border-border-subtle my-2"></div>

        <a class="flex items-center gap-3 px-4 py-3 text-muted-gold hover:text-heritage-burgundy rounded-lg transition-colors"
            href="{{ route('collections') }}" target="_blank">
            <span class="material-symbols-outlined text-xl">open_in_new</span>
            <span>View Storefront</span>
        </a>
    </nav>

    <!-- Footer Branding & Logout -->
    <div class="px-6 pt-4 border-t border-border-subtle flex justify-between items-center">
        <span class="font-headline-md text-base font-semibold text-heritage-burgundy">Sonakshi</span>
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="text-xs text-on-surface-variant hover:text-error transition-colors flex items-center gap-1 cursor-pointer" title="Logout">
                <span class="material-symbols-outlined text-base">logout</span>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>
