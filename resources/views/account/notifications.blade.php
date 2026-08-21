<x-layouts.app title="Notifications - Sonakshi Fashion Hub">
    <div class="max-w-6xl mx-auto py-6 md:py-10 space-y-8">
        <!-- Account Header Banner -->
        <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 md:p-8 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-cream-silk text-heritage-burgundy flex items-center justify-center font-bold font-headline-md text-xl border-2 border-muted-gold/40">
                    <span class="material-symbols-outlined text-2xl">notifications</span>
                </div>
                <div>
                    <h1 class="font-headline-md text-xl md:text-2xl text-heritage-burgundy">Notifications</h1>
                    <p class="font-body-md text-xs text-on-surface-variant mt-0.5">{{ $notifications->total() }} total</p>
                </div>
            </div>

            <!-- Account Nav Tabs -->
            <div class="flex items-center gap-2 border-t md:border-t-0 pt-3 md:pt-0 border-border-subtle">
                <a href="{{ route('account.orders') }}" class="px-4 py-2 rounded-xl text-xs font-label-caps uppercase font-bold text-on-surface-variant hover:bg-surface-container transition-colors">
                    My Orders
                </a>
                <a href="{{ route('wishlist') }}" class="px-4 py-2 rounded-xl text-xs font-label-caps uppercase font-bold text-on-surface-variant hover:bg-surface-container transition-colors">
                    My Wishlist
                </a>
                <a href="{{ route('account.notifications') }}" class="px-4 py-2 rounded-xl text-xs font-label-caps uppercase font-bold bg-heritage-burgundy text-white shadow-xs">
                    Notifications
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

        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="font-headline-md text-lg text-charcoal-text">All Activity</h2>
                @if($notifications->contains(fn($n) => $n->read_at === null))
                    <form action="{{ route('account.notifications.read-all') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs font-label-caps uppercase text-heritage-burgundy hover:underline font-bold cursor-pointer">
                            Mark All Read
                        </button>
                    </form>
                @endif
            </div>

            @forelse($notifications as $notification)
                <form action="{{ route('account.notifications.read', $notification->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left cursor-pointer">
                        <div class="flex items-start gap-4 bg-surface-container-lowest rounded-2xl border {{ $notification->read_at ? 'border-border-subtle' : 'border-heritage-burgundy/40' }} p-5 shadow-xs hover:border-heritage-burgundy/30 transition-all">
                            <div class="w-10 h-10 rounded-full bg-cream-silk text-heritage-burgundy flex items-center justify-center shrink-0 border border-muted-gold/30">
                                <span class="material-symbols-outlined text-lg">{{ $notification->data['icon'] ?? 'notifications' }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-semibold text-sm text-charcoal-text">{{ $notification->data['title'] ?? 'Notification' }}</h3>
                                    @if(! $notification->read_at)
                                        <span class="w-2 h-2 rounded-full bg-heritage-burgundy"></span>
                                    @endif
                                </div>
                                <p class="text-xs text-on-surface-variant mt-1">{{ $notification->data['message'] ?? '' }}</p>
                                <p class="text-[11px] text-on-surface-variant/70 font-data-tabular mt-1.5">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </button>
                </form>
            @empty
                <div class="text-center py-16 bg-surface-container-lowest rounded-2xl border border-border-subtle p-8">
                    <div class="w-16 h-16 rounded-full bg-cream-silk text-heritage-burgundy flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-3xl">notifications_off</span>
                    </div>
                    <h3 class="font-headline-md text-lg text-charcoal-text">No Notifications Yet</h3>
                    <p class="font-body-md text-xs text-on-surface-variant mt-1">Order updates, offers, and wishlist alerts will show up here.</p>
                </div>
            @endforelse

            @if($notifications->hasPages())
                <div class="pt-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
