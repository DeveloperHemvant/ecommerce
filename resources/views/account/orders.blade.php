<x-layouts.app title="My Orders - Sonakshi Fashion Hub">
    <div class="max-w-6xl mx-auto py-6 md:py-10 space-y-8">
        <!-- Account Header Banner -->
        <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 md:p-8 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-cream-silk text-heritage-burgundy flex items-center justify-center font-bold font-headline-md text-xl border-2 border-muted-gold/40">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <h1 class="font-headline-md text-xl md:text-2xl text-heritage-burgundy">Hello, {{ $user->name }}</h1>
                    <p class="font-body-md text-xs text-on-surface-variant mt-0.5">{{ $user->email }} &bull; {{ $user->orders->count() }} Total {{ Str::plural('Order', $user->orders->count()) }}</p>
                </div>
            </div>

            <!-- Account Nav Tabs -->
            <div class="flex items-center gap-2 border-t md:border-t-0 pt-3 md:pt-0 border-border-subtle">
                <a href="{{ route('account.orders') }}" class="px-4 py-2 rounded-xl text-xs font-label-caps uppercase font-bold bg-heritage-burgundy text-white shadow-xs">
                    My Orders
                </a>
                <a href="{{ route('wishlist') }}" class="px-4 py-2 rounded-xl text-xs font-label-caps uppercase font-bold text-on-surface-variant hover:bg-surface-container transition-colors">
                    My Wishlist
                </a>
                <a href="{{ route('account.profile') }}" class="px-4 py-2 rounded-xl text-xs font-label-caps uppercase font-bold text-on-surface-variant hover:bg-surface-container transition-colors">
                    Profile &amp; Settings
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-800 text-xs rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-base">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Orders List -->
        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="font-headline-md text-lg text-charcoal-text">Your Order History</h2>
                <a href="{{ route('collections') }}" class="text-xs font-label-caps uppercase text-heritage-burgundy hover:underline font-bold">
                    Explore New Collections &rarr;
                </a>
            </div>

            @forelse($orders as $order)
                <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 shadow-xs space-y-5 hover:border-heritage-burgundy/30 transition-all">
                    <!-- Order Card Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-border-subtle pb-4 text-xs">
                        <div class="space-y-1">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('account.orders.show', $order->order_number) }}" class="font-headline-md text-base text-heritage-burgundy hover:underline">
                                    Order #{{ $order->order_number }}
                                </a>
                                @php
                                    $statusStyles = [
                                        'processing' => 'bg-blue-50 text-blue-800 border-blue-200',
                                        'packed' => 'bg-purple-50 text-purple-800 border-purple-200',
                                        'shipped' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
                                        'delivered' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                        'cancelled' => 'bg-red-50 text-error border-red-200',
                                    ];
                                    $style = $statusStyles[$order->status] ?? 'bg-gray-50 text-gray-800 border-gray-200';
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-label-caps uppercase font-bold border {{ $style }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <p class="text-on-surface-variant font-data-tabular">
                                Placed on {{ $order->created_at->format('M d, Y') }} &bull; Total: <strong class="text-charcoal-text font-bold">{{ $order->formatted_total }}</strong>
                            </p>
                        </div>

                        <div class="flex items-center gap-2">
                            @if($order->tracking_number)
                                <a href="{{ route('track.order', ['order' => $order->order_number]) }}" class="px-3 py-1.5 bg-cream-silk text-heritage-burgundy rounded-lg border border-muted-gold/40 text-xs font-label-caps uppercase font-bold hover:bg-heritage-burgundy hover:text-white transition-colors">
                                    Live Tracking
                                </a>
                            @endif
                            <a href="{{ route('account.orders.show', $order->order_number) }}" class="px-3.5 py-1.5 bg-surface-container text-charcoal-text rounded-lg border border-border-subtle text-xs font-label-caps uppercase font-bold hover:bg-surface-container-high transition-colors">
                                View Order Details
                            </a>
                        </div>
                    </div>

                    <!-- Items Preview -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($order->items as $item)
                            <div class="flex gap-3 items-center p-3 bg-warm-ivory/40 rounded-xl border border-border-subtle/60">
                                <div class="w-12 h-16 rounded-lg overflow-hidden bg-surface shrink-0 border border-border-subtle">
                                    <img src="{{ $item->product_image ?? ($item->product->main_image ?? '') }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover" />
                                </div>
                                <div class="min-w-0 flex-1 text-xs">
                                    <h4 class="font-semibold text-charcoal-text truncate">{{ $item->product_name }}</h4>
                                    <p class="text-[11px] text-on-surface-variant">Size: <strong>{{ $item->size }}</strong> &bull; Qty: {{ $item->quantity }}</p>
                                    <p class="font-data-tabular text-heritage-burgundy font-bold mt-0.5">{{ $item->formatted_total }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-surface-container-lowest rounded-2xl border border-border-subtle p-8">
                    <div class="w-16 h-16 rounded-full bg-cream-silk text-heritage-burgundy flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-3xl">shopping_bag</span>
                    </div>
                    <h3 class="font-headline-md text-lg text-charcoal-text">No Orders Placed Yet</h3>
                    <p class="font-body-md text-xs text-on-surface-variant mt-1">Explore our royal collections of Lachas, Lehengas, and Suits to place your first couture order.</p>
                    <a href="{{ route('collections') }}" class="inline-block mt-4 text-xs font-label-caps text-white bg-heritage-burgundy px-6 py-3 rounded-xl uppercase font-bold hover:bg-primary-container transition-all">
                        Browse Collections
                    </a>
                </div>
            @endforelse

            @if($orders->hasPages())
                <div class="pt-4">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
