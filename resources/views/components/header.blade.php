@php
    $cart = session()->get('cart', []);
    $cartCount = array_sum(array_column($cart, 'quantity'));

    $wishlistCount = 0;
    if (Auth::check()) {
        $wishlistCount = Auth::user()->wishlists()->count();
    } else {
        $wishlistCount = count(session('wishlist', []));
    }

    $navCategories = \App\Models\Category::where('is_active', true)
        ->orderBy('display_order', 'asc')
        ->get(['name', 'slug']);
@endphp

<header
    class="fixed top-0 w-full z-50 bg-background/85 backdrop-blur-md border-b border-border-subtle flex justify-between items-center px-margin-mobile md:px-margin-desktop h-16 transition-opacity opacity-100">
    <div class="flex items-center gap-6 text-heritage-burgundy">
        <a href="{{ route('home') }}" class="font-headline-md text-2xl md:text-headline-md font-semibold text-heritage-burgundy tracking-tight hover:opacity-90 transition-opacity">
            Sonakshi
        </a>
        <nav class="hidden md:flex items-center gap-6 ml-4">
            <div class="relative" id="header-collections-widget">
                <button type="button" id="header-collections-toggle" aria-haspopup="true" aria-expanded="false"
                    class="flex items-center gap-1 font-label-caps text-xs text-on-surface-variant hover:text-heritage-burgundy transition-colors tracking-widest uppercase cursor-pointer {{ request()->is('collections*') ? 'text-heritage-burgundy font-bold' : '' }}">
                    <span>Collections</span>
                    <span id="header-collections-caret" class="material-symbols-outlined text-sm transition-transform">expand_more</span>
                </button>

                <div id="header-collections-panel" class="hidden absolute left-0 top-full pt-3 z-50">
                    <div class="w-60 bg-surface-container-lowest border border-border-subtle rounded-2xl shadow-lg p-2">
                        <a href="{{ route('collections') }}" class="block px-4 py-2.5 rounded-xl text-xs font-bold text-charcoal-text hover:bg-warm-ivory/70 hover:text-heritage-burgundy transition-colors">
                            All Collections
                        </a>
                        <div class="my-1 border-t border-border-subtle"></div>
                        @foreach($navCategories as $navCategory)
                            <a href="{{ route('collections', ['category' => $navCategory->slug]) }}" class="block px-4 py-2.5 rounded-xl text-xs text-on-surface-variant hover:bg-warm-ivory/70 hover:text-heritage-burgundy transition-colors">
                                {{ $navCategory->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <a href="{{ route('about') }}" class="font-label-caps text-xs text-on-surface-variant hover:text-heritage-burgundy transition-colors tracking-widest uppercase {{ request()->is('about-us*') ? 'text-heritage-burgundy font-bold' : '' }}">
                About Us
            </a>
            <a href="{{ route('track.order') }}" class="font-label-caps text-xs text-on-surface-variant hover:text-heritage-burgundy transition-colors tracking-widest uppercase {{ request()->is('track-order*') ? 'text-heritage-burgundy font-bold' : '' }}">
                Track Order
            </a>
            <a href="{{ route('contact') }}" class="font-label-caps text-xs text-on-surface-variant hover:text-heritage-burgundy transition-colors tracking-widest uppercase {{ request()->is('contact-us*') ? 'text-heritage-burgundy font-bold' : '' }}">
                Contact
            </a>
            @if(Auth::check() && Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="font-label-caps text-xs text-muted-gold hover:text-heritage-burgundy transition-colors tracking-widest uppercase">
                    Admin Panel
                </a>
            @endif
        </nav>
    </div>
    
    <div class="flex items-center gap-4">
        <!-- Search -->
        <div class="relative" id="header-search-widget">
            <button type="button" id="header-search-toggle"
                class="text-on-surface-variant hover:text-heritage-burgundy transition-colors duration-300"
                title="Search Products">
                <span class="material-symbols-outlined text-xl">search</span>
            </button>

            <div id="header-search-panel"
                class="hidden absolute right-0 mt-3 w-[calc(100vw-2.5rem)] max-w-sm bg-surface-container-lowest border border-border-subtle rounded-2xl shadow-lg p-3 z-50">
                <form action="{{ route('collections') }}" method="GET" class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-base">search</span>
                    <input type="text" id="header-search-input" name="search" autocomplete="off"
                        placeholder="Search garments, fabric, SKU..."
                        class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl pl-9 pr-4 py-2.5 font-body-md text-xs text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors" />
                </form>
                <div id="header-search-results" class="mt-2 max-h-80 overflow-y-auto divide-y divide-border-subtle"></div>
            </div>
        </div>

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

<script>
    (function () {
        var toggle = document.getElementById('header-search-toggle');
        var panel = document.getElementById('header-search-panel');
        var input = document.getElementById('header-search-input');
        var results = document.getElementById('header-search-results');
        var debounceTimer = null;

        var collectionsToggle = document.getElementById('header-collections-toggle');
        var collectionsPanel = document.getElementById('header-collections-panel');
        var collectionsCaret = document.getElementById('header-collections-caret');

        if (collectionsToggle && collectionsPanel) {
            collectionsToggle.addEventListener('click', function (event) {
                event.stopPropagation();
                var isOpen = !collectionsPanel.classList.contains('hidden');
                collectionsPanel.classList.toggle('hidden');
                collectionsToggle.setAttribute('aria-expanded', String(!isOpen));
                if (collectionsCaret) {
                    collectionsCaret.style.transform = isOpen ? '' : 'rotate(180deg)';
                }
                if (panel) panel.classList.add('hidden');
            });

            document.addEventListener('click', function (event) {
                if (!collectionsPanel.contains(event.target) && !collectionsToggle.contains(event.target)) {
                    collectionsPanel.classList.add('hidden');
                    collectionsToggle.setAttribute('aria-expanded', 'false');
                    if (collectionsCaret) collectionsCaret.style.transform = '';
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    collectionsPanel.classList.add('hidden');
                    collectionsToggle.setAttribute('aria-expanded', 'false');
                    if (collectionsCaret) collectionsCaret.style.transform = '';
                }
            });
        }

        if (!toggle || !panel || !input || !results) return;

        toggle.addEventListener('click', function (event) {
            event.stopPropagation();
            panel.classList.toggle('hidden');
            if (!panel.classList.contains('hidden')) {
                input.focus();
                if (collectionsPanel) collectionsPanel.classList.add('hidden');
            }
        });

        document.addEventListener('click', function (event) {
            if (!panel.contains(event.target) && !toggle.contains(event.target)) {
                panel.classList.add('hidden');
            }
        });

        input.addEventListener('input', function () {
            var term = input.value.trim();
            clearTimeout(debounceTimer);

            if (term.length < 2) {
                results.innerHTML = '';
                return;
            }

            debounceTimer = setTimeout(function () {
                fetch("{{ route('search.suggest') }}?q=" + encodeURIComponent(term))
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        results.textContent = '';
                        var items = data.results || [];

                        items.forEach(function (item) {
                            var link = document.createElement('a');
                            link.href = item.url;
                            link.className = 'flex items-center gap-3 py-2 px-1 hover:bg-warm-ivory/60 rounded-lg transition-colors';

                            var img = document.createElement('img');
                            img.src = item.image;
                            img.className = 'w-10 h-12 object-cover rounded-lg shrink-0 border border-border-subtle';

                            var textWrap = document.createElement('span');
                            textWrap.className = 'flex-1 min-w-0';

                            var nameEl = document.createElement('span');
                            nameEl.className = 'block text-xs font-semibold text-charcoal-text truncate';
                            nameEl.textContent = item.name;

                            var priceEl = document.createElement('span');
                            priceEl.className = 'block text-[11px] text-heritage-burgundy font-bold';
                            priceEl.textContent = item.price;

                            textWrap.appendChild(nameEl);
                            textWrap.appendChild(priceEl);
                            link.appendChild(img);
                            link.appendChild(textWrap);
                            results.appendChild(link);
                        });

                        if (items.length === 0) {
                            var empty = document.createElement('p');
                            empty.className = 'text-[11px] text-on-surface-variant italic py-2 px-1';
                            empty.textContent = 'No pieces found.';
                            results.appendChild(empty);
                        }
                    });
            }, 250);
        });
    })();
</script>
