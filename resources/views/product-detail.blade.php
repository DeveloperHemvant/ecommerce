<x-layouts.app :title="$product->name . ' - Sonakshi Fashion Hub'">
    <!-- Breadcrumbs -->
    <nav aria-label="Breadcrumb" class="flex text-on-surface-variant font-label-caps text-xs mb-6">
        <ol class="inline-flex items-center space-x-1 md:space-x-2">
            <li><a href="{{ route('home') }}" class="hover:text-heritage-burgundy transition-colors">Home</a></li>
            <li><span class="material-symbols-outlined text-[16px] mx-1">chevron_right</span></li>
            <li>
                <a href="{{ route('collections', ['category' => $product->category->slug ?? '']) }}" class="hover:text-heritage-burgundy transition-colors">
                    {{ $product->category->name ?? 'Collections' }}
                </a>
            </li>
            <li><span class="material-symbols-outlined text-[16px] mx-1">chevron_right</span></li>
            <li aria-current="page" class="text-charcoal-text font-semibold truncate max-w-xs">{{ $product->name }}</li>
        </ol>
    </nav>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 text-xs rounded-xl flex items-center gap-2">
            <span class="material-symbols-outlined text-base">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-error text-xs rounded-xl flex items-center gap-2">
            <span class="material-symbols-outlined text-base">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

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
            <div class="flex-1 rounded-2xl overflow-hidden relative aspect-[3/4] md:aspect-auto md:h-[700px] bg-surface-container-low shadow-sm group">
                <img id="mainProductImage" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                    data-alt="{{ $product->name }}"
                    src="{{ $gallery[0] ?? $product->main_image }}" />

                @if($product->discount_percentage)
                    <div class="absolute top-4 left-4 bg-heritage-burgundy text-white font-label-caps text-xs px-3 py-1 rounded-full font-bold shadow-md">
                        {{ $product->discount_percentage }}% OFF
                    </div>
                @endif

                <!-- Wishlist Heart Button -->
                <form action="{{ route('wishlist.toggle') }}" method="POST" class="absolute top-4 right-4 z-10">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}" />
                    @php
                        $isWishlisted = false;
                        if(Auth::check()) {
                            $isWishlisted = Auth::user()->wishlists()->where('product_id', $product->id)->exists();
                        } else {
                            $isWishlisted = in_array($product->id, session('wishlist', []));
                        }
                    @endphp
                    <button type="submit" class="w-10 h-10 rounded-full bg-white/90 backdrop-blur-md {{ $isWishlisted ? 'text-error' : 'text-on-surface-variant hover:text-error' }} flex items-center justify-center shadow-md hover:scale-110 transition-all cursor-pointer" title="{{ $isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                        <span class="material-symbols-outlined text-xl" @if($isWishlisted) style="font-variation-settings: 'FILL' 1;" @endif>favorite</span>
                    </button>
                </form>
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
                <div class="flex text-amber-500">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                    @endfor
                </div>
                <a href="#reviewsSection" class="font-body-md text-xs text-on-surface-variant underline cursor-pointer hover:text-heritage-burgundy transition-colors">
                    {{ $product->reviews_count > 0 ? $product->reviews_count : ($product->reviews->count() ?: 12) }} Customer Reviews
                </a>
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

                <!-- Custom Fit Indicator Notice -->
                <div id="customFitNotice" class="hidden mt-3 p-3 bg-cream-silk rounded-xl border border-muted-gold/40 text-xs flex items-center justify-between">
                    <div>
                        <span class="font-bold text-heritage-burgundy block">Bespoke Atelier Fit Selected</span>
                        <span class="text-on-surface-variant text-[11px]">Enter your custom measurements for an exact bridal fit.</span>
                    </div>
                    <button type="button" onclick="openCustomFitModal()" class="px-3 py-1 bg-heritage-burgundy text-white rounded-lg text-[10px] font-label-caps uppercase font-bold">
                        Set Measurements
                    </button>
                </div>
            </div>

            <!-- Primary CTAs Form -->
            <form action="{{ route('cart.add') }}" method="POST" id="addToCartForm" class="flex flex-col gap-3.5 mb-8">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}" />
                <input type="hidden" name="size" id="selectedSizeInput" value="{{ $sizes[0] ?? 'Standard' }}" />
                <input type="hidden" name="quantity" value="1" />

                <!-- Hidden Custom Measurements inputs injected by modal -->
                <div id="customMeasurementsContainer"></div>

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

                <a id="waOrderBtn" href="https://wa.me/?text={{ urlencode('Hi Sonakshi Team, I am interested in: ' . $product->name . ' (' . $product->sku . ')') }}" target="_blank"
                    class="w-full py-3.5 rounded-xl border border-[#25D366] text-[#25D366] font-label-caps text-xs uppercase tracking-wider hover:bg-[#25D366]/10 transition-colors flex items-center justify-center gap-2 cursor-pointer font-bold">
                    <span class="material-symbols-outlined text-lg">chat</span>
                    Order on WhatsApp
                </a>
            </form>

            <!-- Product Details Tabs -->
            <div class="border-t border-border-subtle mt-2">
                <div class="flex border-b border-border-subtle">
                    <button type="button" onclick="switchTab('desc', this)" id="tabBtnDesc" class="tab-btn py-3 px-4 font-title-lg text-sm font-semibold text-heritage-burgundy border-b-2 border-heritage-burgundy -mb-[1px]">Description</button>
                    <button type="button" onclick="switchTab('fabric', this)" id="tabBtnFabric" class="tab-btn py-3 px-4 font-title-lg text-sm font-semibold text-on-surface-variant hover:text-heritage-burgundy transition-colors">Fabric &amp; Care</button>
                    <button type="button" onclick="switchTab('shipping', this)" id="tabBtnShipping" class="tab-btn py-3 px-4 font-title-lg text-sm font-semibold text-on-surface-variant hover:text-heritage-burgundy transition-colors">Shipping</button>
                </div>
                
                <div id="tabContentDesc" class="py-5 font-body-md text-sm text-charcoal-text leading-relaxed">
                    <p class="mb-4">
                        {{ $product->description ?? 'Embrace the grandeur of heritage with this handcrafted ensemble from Sonakshi Fashion Hub. Intricately woven by master artisans, this piece is designed for timeless royal celebrations.' }}
                    </p>
                    <ul class="list-disc pl-5 space-y-1.5 text-on-surface-variant text-xs">
                        <li>SKU Code: <strong>{{ $product->sku }}</strong></li>
                        <li>Category: <strong>{{ $product->category->name ?? 'Ethnic' }}</strong></li>
                        @if(is_array($product->colors) && count($product->colors) > 0)
                            <li>Colors: <strong>{{ implode(', ', $product->colors) }}</strong></li>
                        @endif
                        <li>Custom tailoring available on demand.</li>
                    </ul>
                </div>

                <div id="tabContentFabric" class="py-5 font-body-md text-sm text-charcoal-text leading-relaxed hidden">
                    <p class="text-xs text-on-surface-variant">
                        {{ $product->fabric_care ?? 'Pure Silk & Zari Weave. Professional Dry Clean Only. Store wrapped in muslin cloth away from dampness and direct sunlight.' }}
                    </p>
                </div>

                <div id="tabContentShipping" class="py-5 font-body-md text-sm text-charcoal-text leading-relaxed hidden">
                    <p class="text-xs text-on-surface-variant">
                        {{ $product->shipping_info ?? 'Free insured standard shipping across India. Standard orders dispatch in 24-48 hours. Express delivery available in 3-5 business days.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Verified Customer Reviews & Photos Section -->
    <section id="reviewsSection" class="bg-surface-container-low rounded-2xl p-6 md:p-12 mb-16 border border-border-subtle/70">
        <div class="max-w-5xl mx-auto space-y-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-border-subtle pb-6">
                <div>
                    <span class="font-label-caps text-xs text-muted-gold uppercase tracking-widest font-bold">Social Proof &amp; Brides</span>
                    <h2 class="font-headline-md text-2xl text-heritage-burgundy">Customer Reviews &amp; Testimonials</h2>
                </div>
                <button type="button" onclick="document.getElementById('writeReviewCard').classList.toggle('hidden')" class="px-5 py-2.5 bg-heritage-burgundy text-white rounded-xl text-xs font-label-caps uppercase font-bold hover:bg-primary-container transition-all self-start md:self-auto cursor-pointer shadow-xs">
                    Write a Review &amp; Upload Photo
                </button>
            </div>

            <!-- Submit Review Form -->
            <div id="writeReviewCard" class="hidden bg-surface-container-lowest p-6 rounded-2xl border border-border-subtle shadow-xs space-y-4">
                <h3 class="font-title-lg text-base font-semibold text-charcoal-text">Share Your Experience with {{ $product->name }}</h3>
                <form action="{{ route('reviews.store', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-label-caps uppercase text-on-surface-variant font-semibold mb-1">Your Name <span class="text-error">*</span></label>
                            <input type="text" name="customer_name" value="{{ Auth::check() ? Auth::user()->name : '' }}" required
                                class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-2.5 text-xs text-charcoal-text focus:border-heritage-burgundy focus:outline-none" />
                        </div>

                        <div>
                            <label class="block text-xs font-label-caps uppercase text-on-surface-variant font-semibold mb-1">Star Rating (1-5) <span class="text-error">*</span></label>
                            <select name="rating" required class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-2.5 text-xs text-charcoal-text focus:border-heritage-burgundy focus:outline-none">
                                <option value="5">5 Stars - Outstanding Royal Fit</option>
                                <option value="4">4 Stars - Very Beautiful</option>
                                <option value="3">3 Stars - Good Quality</option>
                                <option value="2">2 Stars - Average</option>
                                <option value="1">1 Star - Not as expected</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-label-caps uppercase text-on-surface-variant font-semibold mb-1">Review Headline</label>
                        <input type="text" name="title" placeholder="e.g. Wore this to my sister's Sangeet, received so many compliments!"
                            class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-2.5 text-xs text-charcoal-text focus:border-heritage-burgundy focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-xs font-label-caps uppercase text-on-surface-variant font-semibold mb-1">Detailed Review <span class="text-error">*</span></label>
                        <textarea name="comment" rows="3" required placeholder="Describe the silk fabric, drape, fitting, and packaging..."
                            class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-2.5 text-xs text-charcoal-text focus:border-heritage-burgundy focus:outline-none"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-label-caps uppercase text-on-surface-variant font-semibold mb-1">Upload Your Outfit Photos (Optional)</label>
                        <input type="file" name="photos[]" multiple accept="image/*"
                            class="text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-heritage-burgundy file:text-white cursor-pointer" />
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('writeReviewCard').classList.add('hidden')" class="px-4 py-2 text-xs font-label-caps uppercase text-on-surface-variant">Cancel</button>
                        <button type="submit" class="bg-heritage-burgundy text-white px-6 py-2.5 rounded-xl font-label-caps text-xs uppercase font-bold hover:bg-primary-container cursor-pointer shadow-xs">
                            Submit Review
                        </button>
                    </div>
                </form>
            </div>

            <!-- Reviews List -->
            @php
                $approvedReviews = $product->reviews;
            @endphp
            @if($approvedReviews->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($approvedReviews as $rev)
                        <div class="bg-surface-container-lowest p-6 rounded-2xl border border-border-subtle shadow-xs space-y-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="flex text-amber-500 mb-1">
                                        @for($s = 1; $s <= 5; $s++)
                                            <span class="material-symbols-outlined text-sm {{ $s <= $rev->rating ? 'text-amber-500' : 'text-gray-300' }}" style="font-variation-settings: 'FILL' 1;">star</span>
                                        @endfor
                                    </div>
                                    <h4 class="font-bold text-charcoal-text text-sm">{{ $rev->title ?? 'Heritage Royal Fit' }}</h4>
                                </div>
                                <span class="text-[10px] text-on-surface-variant font-data-tabular">{{ $rev->created_at->format('M Y') }}</span>
                            </div>

                            <p class="text-xs text-charcoal-text leading-relaxed">{{ $rev->comment }}</p>

                            @if(!empty($rev->photos))
                                <div class="flex gap-2 pt-2 overflow-x-auto">
                                    @foreach($rev->photos as $p)
                                        <a href="{{ $p }}" target="_blank" class="w-14 h-18 rounded-lg overflow-hidden border border-border-subtle shrink-0">
                                            <img src="{{ $p }}" class="w-full h-full object-cover" />
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            <div class="flex items-center gap-2 pt-2 border-t border-border-subtle/50 text-[11px] text-on-surface-variant">
                                <span class="font-bold text-charcoal-text">{{ $rev->customer_name }}</span>
                                @if($rev->is_verified_buyer)
                                    <span class="text-emerald-800 font-semibold inline-flex items-center gap-0.5">
                                        <span class="material-symbols-outlined text-xs">verified</span>
                                        Verified Buyer
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10 bg-surface-container-lowest rounded-2xl border border-border-subtle p-6">
                    <span class="material-symbols-outlined text-3xl text-muted-gold mb-1 block">rate_review</span>
                    <p class="font-semibold text-charcoal-text text-sm">Be the first to review this heritage ensemble!</p>
                    <p class="text-xs text-on-surface-variant mt-0.5">Share your feedback and styling photos with our royal community.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Custom Fit Tailoring Modal -->
    <div id="customFitModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-surface-container-lowest border border-border-subtle rounded-2xl max-w-lg w-full p-6 md:p-8 shadow-2xl relative space-y-4">
            <div class="flex justify-between items-center border-b border-border-subtle pb-3">
                <div>
                    <span class="font-label-caps text-[10px] uppercase text-muted-gold font-bold">Atelier Bespoke Tailoring</span>
                    <h3 class="font-headline-md text-xl text-heritage-burgundy font-semibold">Custom Fit Measurements</h3>
                </div>
                <button type="button" onclick="closeCustomFitModal()" class="text-on-surface-variant hover:text-charcoal-text p-1 cursor-pointer">
                    <span class="material-symbols-outlined text-2xl">close</span>
                </button>
            </div>

            <p class="text-xs font-body-md text-on-surface-variant">
                Enter your exact measurements in inches. Our senior master tailor will craft your garment accordingly.
            </p>

            <div class="grid grid-cols-2 gap-3 text-xs">
                <div>
                    <label class="block font-semibold mb-1">Blouse Bust (in)</label>
                    <input type="number" id="mf_blouse_bust" placeholder="e.g. 36" step="0.5" class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl p-2 font-data-tabular focus:border-heritage-burgundy focus:outline-none" />
                </div>
                <div>
                    <label class="block font-semibold mb-1">Blouse Waist (in)</label>
                    <input type="number" id="mf_blouse_waist" placeholder="e.g. 30" step="0.5" class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl p-2 font-data-tabular focus:border-heritage-burgundy focus:outline-none" />
                </div>
                <div>
                    <label class="block font-semibold mb-1">Sleeve Length (in)</label>
                    <input type="number" id="mf_sleeve_length" placeholder="e.g. 10.5" step="0.5" class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl p-2 font-data-tabular focus:border-heritage-burgundy focus:outline-none" />
                </div>
                <div>
                    <label class="block font-semibold mb-1">Skirt / Bottom Waist (in)</label>
                    <input type="number" id="mf_skirt_waist" placeholder="e.g. 32" step="0.5" class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl p-2 font-data-tabular focus:border-heritage-burgundy focus:outline-none" />
                </div>
                <div class="col-span-2">
                    <label class="block font-semibold mb-1">Skirt Length (Waist to Floor) (in)</label>
                    <input type="number" id="mf_skirt_length" placeholder="e.g. 42.5" step="0.5" class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl p-2 font-data-tabular focus:border-heritage-burgundy focus:outline-none" />
                </div>
            </div>

            <div class="pt-2 flex justify-end gap-3 border-t border-border-subtle">
                <button type="button" onclick="saveCustomMeasurements()" class="bg-heritage-burgundy text-white px-6 py-2.5 rounded-xl font-label-caps text-xs uppercase font-bold cursor-pointer">
                    Save Measurements
                </button>
            </div>
        </div>
    </div>

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
                All measurements are indicated in inches.
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

    <!-- Client Scripts -->
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

            const notice = document.getElementById('customFitNotice');
            if (size.toUpperCase().includes('CUSTOM')) {
                notice.classList.remove('hidden');
                openCustomFitModal();
            } else {
                notice.classList.add('hidden');
            }
        }

        function openCustomFitModal() {
            document.getElementById('customFitModal').classList.remove('hidden');
        }

        function closeCustomFitModal() {
            document.getElementById('customFitModal').classList.add('hidden');
        }

        function saveCustomMeasurements() {
            const container = document.getElementById('customMeasurementsContainer');
            container.innerHTML = `
                <input type="hidden" name="custom_measurements[blouse_bust]" value="${document.getElementById('mf_blouse_bust').value || '36'}" />
                <input type="hidden" name="custom_measurements[blouse_waist]" value="${document.getElementById('mf_blouse_waist').value || '30'}" />
                <input type="hidden" name="custom_measurements[sleeve_length]" value="${document.getElementById('mf_sleeve_length').value || '10'}" />
                <input type="hidden" name="custom_measurements[skirt_waist]" value="${document.getElementById('mf_skirt_waist').value || '32'}" />
                <input type="hidden" name="custom_measurements[skirt_length]" value="${document.getElementById('mf_skirt_length').value || '42'}" />
            `;
            closeCustomFitModal();
            alert('Custom atelier tailoring measurements saved for your order!');
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
