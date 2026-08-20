<x-layouts.app :title="$product->name . ' - Sonakshi Fashion Hub'">
    <!-- Breadcrumbs -->
    <nav aria-label="Breadcrumb" class="flex text-on-surface-variant font-label-caps text-xs mb-6">
        <ol class="inline-flex items-center space-x-1 md:space-x-2">
            <li><a href="{{ route('home') }}" class="hover:text-heritage-burgundy transition-colors">Home</a></li>
            <li><span class="material-symbols-outlined text-[16px] mx-1">chevron_right</span></li>
            <li>
                <a href="{{ route('collections', ['category' => $product->category->slug]) }}" class="hover:text-heritage-burgundy transition-colors">
                    {{ $product->category->name }}
                </a>
            </li>
            <li><span class="material-symbols-outlined text-[16px] mx-1">chevron_right</span></li>
            <li aria-current="page" class="text-charcoal-text font-semibold truncate max-w-xs">{{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Product Showcase Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-16 mb-16">
        <!-- Image Gallery (Left - 7 cols) -->
        <div class="lg:col-span-7 flex flex-col md:flex-row gap-4">
            <!-- Thumbnails (Desktop) -->
            @php
                $gallery = is_array($product->gallery_images) && count($product->gallery_images) > 0 
                    ? $product->gallery_images 
                    : [$product->main_image];
            @endphp
            <div class="hidden md:flex flex-col gap-4 w-24 overflow-y-auto max-h-[700px] hide-scrollbar" id="thumbnailGallery">
                @foreach($gallery as $idx => $img)
                    <button type="button" onclick="switchMainImage('{{ $img }}', this)"
                        class="thumb-btn w-full aspect-[3/4] rounded-lg border-2 {{ $idx === 0 ? 'border-heritage-burgundy shadow-xs' : 'border-border-subtle opacity-70 hover:opacity-100' }} overflow-hidden focus:outline-none cursor-pointer transition-all">
                        <img class="w-full h-full object-cover" data-alt="{{ $product->name }} angle {{ $idx+1 }}" src="{{ $img }}" />
                    </button>
                @endforeach
            </div>

            <!-- Main Big Image -->
            <div class="flex-1 rounded-xl overflow-hidden relative aspect-[3/4] md:aspect-auto md:h-[700px] bg-surface-container-low shadow-sm group">
                <img id="mainProductImage" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                    data-alt="{{ $product->name }}"
                    src="{{ $gallery[0] ?? $product->main_image }}" />

                @if($product->discount_percentage)
                    <div class="absolute top-4 left-4 bg-heritage-burgundy text-white font-label-caps text-xs px-3 py-1 rounded-full font-bold shadow-md">
                        {{ $product->discount_percentage }}% OFF
                    </div>
                @endif
                
                <!-- Mobile Carousel Indicators -->
                <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 md:hidden">
                    @foreach($gallery as $idx => $img)
                        <button onclick="switchMainImage('{{ $img }}', null)" class="w-2.5 h-2.5 rounded-full {{ $idx === 0 ? 'bg-heritage-burgundy' : 'bg-warm-ivory opacity-70' }}"></button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Product Info (Right - 5 cols) -->
        <div class="lg:col-span-5 flex flex-col pt-2 md:pt-0">
            <div class="flex justify-between items-start mb-2">
                <span class="font-data-tabular text-xs text-on-surface-variant uppercase font-semibold">SKU: {{ $product->sku }}</span>
                @if($product->category)
                    <a href="{{ route('collections', ['category' => $product->category->slug]) }}" class="text-[11px] font-label-caps text-heritage-burgundy font-bold uppercase tracking-wider">
                        {{ $product->category->name }}
                    </a>
                @endif
            </div>

            <h1 class="font-headline-lg text-heritage-burgundy mb-2">
                {{ $product->name }}
            </h1>

            <div class="flex items-center gap-3 mb-6">
                <div class="flex text-muted-gold">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                    @endfor
                </div>
                <span class="font-body-md text-xs text-on-surface-variant underline cursor-pointer hover:text-heritage-burgundy transition-colors">
                    {{ $product->reviews_count > 0 ? $product->reviews_count : 42 }} Customer Reviews
                </span>
            </div>

            <!-- Price & Discount -->
            <div class="flex items-end gap-4 mb-3">
                <span class="font-headline-md text-3xl font-bold text-heritage-burgundy">{{ $product->formatted_price }}</span>
                @if($product->formatted_compare_price)
                    <span class="font-body-md text-on-surface-variant line-through mb-0.5 text-lg">{{ $product->formatted_compare_price }}</span>
                @endif
                @if($product->discount_percentage)
                    <span class="font-label-caps text-xs text-heritage-burgundy font-semibold mb-1 px-2.5 py-0.5 bg-primary-fixed/50 rounded-full border border-primary-fixed">
                        Save {{ $product->discount_percentage }}%
                    </span>
                @endif
            </div>

            <!-- Stock Availability Indicator -->
            <div class="mb-8">
                @if($product->stock <= 0)
                    <p class="font-body-md text-xs text-error font-semibold flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px]">cancel</span>
                        Out of Stock &bull; Currently Unavailable
                    </p>
                @elseif($product->is_low_stock)
                    <p class="font-body-md text-xs text-amber-800 font-semibold flex items-center gap-1.5 bg-amber-50 px-3 py-1.5 rounded-lg border border-amber-200">
                        <span class="material-symbols-outlined text-[18px]">warning</span>
                        Hurry! Only {{ $product->stock }} left in warehouse
                    </p>
                @else
                    <p class="font-body-md text-xs text-emerald-800 font-medium flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px] text-emerald-600" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                        In Stock ({{ $product->stock }} units) &bull; Handcrafted &amp; Ready to Ship
                    </p>
                @endif
            </div>

            <!-- Size Selector -->
            @php
                $sizes = is_array($product->sizes) && count($product->sizes) > 0 
                    ? $product->sizes 
                    : ['S', 'M', 'L', 'XL', 'CUSTOM FIT'];
            @endphp
            <div class="mb-8">
                <div class="flex justify-between items-center mb-3">
                    <span class="font-title-lg text-sm font-semibold text-charcoal-text">Select Size: <span id="selectedSizeLabel" class="text-heritage-burgundy font-bold">{{ $sizes[0] ?? 'Standard' }}</span></span>
                    <button type="button" onclick="openSizeGuide()" class="font-body-md text-xs text-heritage-burgundy underline cursor-pointer hover:text-muted-gold transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">straighten</span>
                        Size Guide
                    </button>
                </div>
                <div class="flex flex-wrap gap-3" id="sizeButtonGroup">
                    @foreach($sizes as $idx => $sz)
                        <button type="button" onclick="selectSize('{{ $sz }}', this)"
                            class="size-btn px-4 h-12 rounded-lg border {{ $idx === 0 ? 'border-2 border-heritage-burgundy bg-heritage-burgundy/10 text-heritage-burgundy font-bold shadow-xs' : 'border-border-subtle hover:border-heritage-burgundy text-charcoal-text' }} flex items-center justify-center font-label-caps text-xs transition-colors cursor-pointer">
                            {{ $sz }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Primary CTAs Form -->
            <form action="{{ route('cart.add') }}" method="POST" class="flex flex-col gap-3.5 mb-10">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}" />
                <input type="hidden" name="size" id="selectedSizeInput" value="{{ $sizes[0] ?? 'Standard' }}" />
                <input type="hidden" name="quantity" value="1" />

                @if($product->stock > 0)
                    <button type="submit"
                        class="w-full py-4 rounded-xl bg-heritage-burgundy text-white font-label-caps text-xs uppercase tracking-wider hover:bg-primary-container transition-all flex items-center justify-center gap-2 shadow-[0px_4px_14px_rgba(96,0,24,0.18)] hover:shadow-lg cursor-pointer font-bold">
                        <span class="material-symbols-outlined text-lg">shopping_cart</span>
                        Add to Cart
                    </button>
                    <button type="submit" name="buy_now" value="1"
                        class="w-full py-4 rounded-xl border border-muted-gold text-heritage-burgundy font-label-caps text-xs uppercase tracking-wider hover:bg-muted-gold/10 transition-colors flex items-center justify-center gap-2 cursor-pointer font-bold">
                        <span class="material-symbols-outlined text-lg">bolt</span>
                        Buy Now
                    </button>
                @else
                    <button disabled type="button" class="w-full py-4 rounded-xl bg-gray-300 text-gray-600 font-label-caps text-xs uppercase tracking-wider flex items-center justify-center gap-2 cursor-not-allowed">
                        Out of Stock
                    </button>
                @endif
                    class="w-full py-3.5 rounded-xl border border-[#25D366] text-[#25D366] font-label-caps text-xs uppercase tracking-wider hover:bg-[#25D366]/10 transition-colors flex items-center justify-center gap-2 cursor-pointer font-bold">
                    <span class="material-symbols-outlined text-lg">chat</span>
                    Order on WhatsApp
                </a>
            </div>

            <!-- Product Details Tabs -->
            <div class="border-t border-border-subtle mt-4">
                <div class="flex border-b border-border-subtle">
                    <button type="button" onclick="switchTab('desc', this)" id="tabBtnDesc" class="tab-btn py-3 px-4 font-title-lg text-sm font-semibold text-heritage-burgundy border-b-2 border-heritage-burgundy -mb-[1px]">Description</button>
                    <button type="button" onclick="switchTab('fabric', this)" id="tabBtnFabric" class="tab-btn py-3 px-4 font-title-lg text-sm font-semibold text-on-surface-variant hover:text-heritage-burgundy transition-colors">Fabric &amp; Care</button>
                    <button type="button" onclick="switchTab('shipping', this)" id="tabBtnShipping" class="tab-btn py-3 px-4 font-title-lg text-sm font-semibold text-on-surface-variant hover:text-heritage-burgundy transition-colors">Shipping</button>
                </div>
                
                <!-- Description Tab -->
                <div id="tabContentDesc" class="py-5 font-body-md text-sm text-charcoal-text leading-relaxed">
                    <p class="mb-4">
                        {{ $product->description ?? 'Embrace the grandeur of heritage with this handcrafted ensemble from Sonakshi Fashion Hub. Intricately woven by master artisans, this piece is designed for timeless royal celebrations.' }}
                    </p>
                    <ul class="list-disc pl-5 space-y-1.5 text-on-surface-variant text-xs">
                        <li>SKU Code: <strong>{{ $product->sku }}</strong></li>
                        <li>Category: <strong>{{ $product->category->name }}</strong></li>
                        @if(is_array($product->colors) && count($product->colors) > 0)
                            <li>Colors: <strong>{{ implode(', ', $product->colors) }}</strong></li>
                        @endif
                        <li>Custom tailoring available on demand.</li>
                    </ul>
                </div>

                <!-- Fabric & Care Tab -->
                <div id="tabContentFabric" class="py-5 font-body-md text-sm text-charcoal-text leading-relaxed hidden">
                    <p class="text-xs text-on-surface-variant">
                        {{ $product->fabric_care ?? 'Pure Silk & Zari Weave. Professional Dry Clean Only. Store wrapped in muslin cloth away from dampness and direct sunlight.' }}
                    </p>
                </div>

                <!-- Shipping Tab -->
                <div id="tabContentShipping" class="py-5 font-body-md text-sm text-charcoal-text leading-relaxed hidden">
                    <p class="text-xs text-on-surface-variant">
                        {{ $product->shipping_info ?? 'Free insured standard shipping across India. Standard orders dispatch in 24-48 hours. Express delivery available in 3-5 business days.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Complete the Look Bento Grid -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <section class="bg-surface-container-low rounded-2xl p-8 md:p-12 mt-12 border border-border-subtle/70">
            <div class="max-w-container-max-width mx-auto">
                <div class="text-center mb-10">
                    <span class="font-label-caps text-xs text-muted-gold uppercase tracking-widest font-semibold block mb-1">Pair &amp; Match</span>
                    <h2 class="font-headline-md text-heritage-burgundy">
                        Complete the Look
                    </h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                    @foreach($relatedProducts as $rel)
                        <a href="{{ route('product.detail', $rel->slug) }}" class="group cursor-pointer">
                            <div class="aspect-[4/5] rounded-xl overflow-hidden relative bg-warm-ivory mb-4 shadow-xs">
                                <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                    data-alt="{{ $rel->name }}"
                                    src="{{ $rel->main_image }}" />
                                <div class="absolute inset-0 bg-heritage-burgundy/0 group-hover:bg-heritage-burgundy/10 transition-colors duration-300"></div>
                            </div>
                            <h3 class="font-title-lg text-base font-semibold text-charcoal-text mb-1 group-hover:text-heritage-burgundy transition-colors line-clamp-1">{{ $rel->name }}</h3>
                            <p class="font-body-md text-sm text-heritage-burgundy font-bold">{{ $rel->formatted_price }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Size Guide Modal -->
    <div id="sizeGuideModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-surface-container-lowest border border-border-subtle rounded-2xl max-w-lg w-full p-6 md:p-8 shadow-2xl relative">
            <div class="flex justify-between items-center mb-6 border-b border-border-subtle pb-3">
                <h3 class="font-headline-md text-xl text-heritage-burgundy font-semibold">Women's Luxury Size Guide</h3>
                <button type="button" onclick="closeSizeGuide()" class="text-on-surface-variant hover:text-charcoal-text p-1 cursor-pointer">
                    <span class="material-symbols-outlined text-2xl">close</span>
                </button>
            </div>

            <p class="text-xs font-body-md text-on-surface-variant mb-4">
                All measurements are indicated in inches. For custom tailoring, choose "CUSTOM FIT" and our stylist will reach out on WhatsApp.
            </p>

            <div class="overflow-x-auto">
                <table class="w-full text-left font-data-tabular text-xs border-collapse">
                    <thead>
                        <tr class="bg-cream-silk border-b border-border-subtle font-label-caps text-heritage-burgundy">
                            <th class="p-2.5 font-bold">Size</th>
                            <th class="p-2.5 font-bold">Bust</th>
                            <th class="p-2.5 font-bold">Waist</th>
                            <th class="p-2.5 font-bold">Hip</th>
                            <th class="p-2.5 font-bold">Skirt Length</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle">
                        <tr>
                            <td class="p-2.5 font-bold text-heritage-burgundy">S (36)</td>
                            <td class="p-2.5">34-35"</td>
                            <td class="p-2.5">28-29"</td>
                            <td class="p-2.5">38-39"</td>
                            <td class="p-2.5">42"</td>
                        </tr>
                        <tr class="bg-warm-ivory/30">
                            <td class="p-2.5 font-bold text-heritage-burgundy">M (38)</td>
                            <td class="p-2.5">36-37"</td>
                            <td class="p-2.5">30-31"</td>
                            <td class="p-2.5">40-41"</td>
                            <td class="p-2.5">42.5"</td>
                        </tr>
                        <tr>
                            <td class="p-2.5 font-bold text-heritage-burgundy">L (40)</td>
                            <td class="p-2.5">38-39"</td>
                            <td class="p-2.5">32-33"</td>
                            <td class="p-2.5">42-43"</td>
                            <td class="p-2.5">43"</td>
                        </tr>
                        <tr class="bg-warm-ivory/30">
                            <td class="p-2.5 font-bold text-heritage-burgundy">XL (42)</td>
                            <td class="p-2.5">40-41"</td>
                            <td class="p-2.5">34-35"</td>
                            <td class="p-2.5">44-45"</td>
                            <td class="p-2.5">43.5"</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 pt-4 border-t border-border-subtle flex justify-end">
                <button type="button" onclick="closeSizeGuide()" class="bg-heritage-burgundy text-white px-5 py-2.5 rounded-xl font-label-caps text-xs uppercase font-bold">
                    Got It
                </button>
            </div>
        </div>
    </div>

    <!-- Interactive Client Scripts -->
    <script>
        function switchMainImage(src, button) {
            const mainImg = document.getElementById('mainProductImage');
            if (mainImg) mainImg.src = src;

            if (button) {
                document.querySelectorAll('.thumb-btn').forEach(btn => {
                    btn.classList.remove('border-heritage-burgundy', 'shadow-xs');
                    btn.classList.add('border-border-subtle', 'opacity-70');
                });
                button.classList.remove('border-border-subtle', 'opacity-70');
                button.classList.add('border-heritage-burgundy', 'shadow-xs');
            }
        }

        let currentSelectedSize = '{{ $sizes[0] ?? "Standard" }}';
        function selectSize(size, button) {
            currentSelectedSize = size;
            document.getElementById('selectedSizeLabel').textContent = size;
            const sizeInput = document.getElementById('selectedSizeInput');
            if (sizeInput) sizeInput.value = size;

            document.querySelectorAll('.size-btn').forEach(btn => {
                btn.className = 'size-btn px-4 h-12 rounded-lg border border-border-subtle hover:border-heritage-burgundy text-charcoal-text flex items-center justify-center font-label-caps text-xs transition-colors cursor-pointer';
            });
            button.className = 'size-btn px-4 h-12 rounded-lg border-2 border-heritage-burgundy bg-heritage-burgundy/10 text-heritage-burgundy font-bold shadow-xs flex items-center justify-center font-label-caps text-xs transition-colors cursor-pointer';

            // Update WhatsApp Link
            const waBtn = document.getElementById('waOrderBtn');
            if (waBtn) {
                const message = encodeURIComponent(`Hi Sonakshi Team, I am interested in ordering: {{ $product->name }} (Size: ${size}, SKU: {{ $product->sku }}) priced at {{ $product->formatted_price }}.`);
                waBtn.href = `https://wa.me/?text=${message}`;
            }
        }

        function openSizeGuide() {
            document.getElementById('sizeGuideModal').classList.remove('hidden');
        }

        function closeSizeGuide() {
            document.getElementById('sizeGuideModal').classList.add('hidden');
        }

        function switchTab(tab, btn) {
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.className = 'tab-btn py-3 px-4 font-title-lg text-sm font-semibold text-on-surface-variant hover:text-heritage-burgundy transition-colors';
            });
            btn.className = 'tab-btn py-3 px-4 font-title-lg text-sm font-semibold text-heritage-burgundy border-b-2 border-heritage-burgundy -mb-[1px]';

            document.getElementById('tabContentDesc').classList.add('hidden');
            document.getElementById('tabContentFabric').classList.add('hidden');
            document.getElementById('tabContentShipping').classList.add('hidden');

            if (tab === 'desc') document.getElementById('tabContentDesc').classList.remove('hidden');
            if (tab === 'fabric') document.getElementById('tabContentFabric').classList.remove('hidden');
            if (tab === 'shipping') document.getElementById('tabContentShipping').classList.remove('hidden');
        }
    </script>
</x-layouts.app>
