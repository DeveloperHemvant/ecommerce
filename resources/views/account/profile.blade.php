<x-layouts.app title="Profile Settings - Sonakshi Fashion Hub">
    <div class="max-w-4xl mx-auto py-6 md:py-10 space-y-8">
        <!-- Account Header Banner -->
        <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 md:p-8 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-cream-silk text-heritage-burgundy flex items-center justify-center font-bold font-headline-md text-xl border-2 border-muted-gold/40">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <h1 class="font-headline-md text-xl md:text-2xl text-heritage-burgundy">{{ $user->name }}</h1>
                    <p class="font-body-md text-xs text-on-surface-variant mt-0.5">{{ $user->email }} &bull; VIP Customer</p>
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
                <a href="{{ route('account.profile') }}" class="px-4 py-2 rounded-xl text-xs font-label-caps uppercase font-bold bg-heritage-burgundy text-white shadow-xs">
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

        @if($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 text-error text-xs rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-base">error</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <!-- Profile Form -->
        <form action="{{ route('account.profile.update') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Personal Info -->
            <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 md:p-8 shadow-xs space-y-5">
                <h2 class="font-title-lg text-base font-semibold text-heritage-burgundy border-b border-border-subtle pb-3">
                    Personal Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="name">
                            Full Name <span class="text-error">*</span>
                        </label>
                        <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                            id="name" name="name" value="{{ old('name', $user->name) }}" required type="text" />
                    </div>

                    <div>
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="email">
                            Email Address <span class="text-error">*</span>
                        </label>
                        <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                            id="email" name="email" value="{{ old('email', $user->email) }}" required type="email" />
                    </div>

                    <div class="md:col-span-2">
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="phone">
                            Phone / WhatsApp Number
                        </label>
                        <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                            id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+91 98765 43210" type="tel" />
                    </div>
                </div>
            </div>

            <!-- Password Security -->
            <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 md:p-8 shadow-xs space-y-5">
                <h2 class="font-title-lg text-base font-semibold text-heritage-burgundy border-b border-border-subtle pb-3">
                    Security &amp; Password Update (Optional)
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="current_password">
                            Current Password
                        </label>
                        <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                            id="current_password" name="current_password" type="password" />
                    </div>

                    <div>
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="new_password">
                            New Password
                        </label>
                        <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                            id="new_password" name="new_password" type="password" />
                    </div>

                    <div>
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="new_password_confirmation">
                            Confirm New Password
                        </label>
                        <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                            id="new_password_confirmation" name="new_password_confirmation" type="password" />
                    </div>
                </div>
            </div>

            <!-- Save Action -->
            <div class="flex justify-end">
                <button type="submit" class="bg-heritage-burgundy text-white font-label-caps text-xs uppercase tracking-wider px-8 py-3.5 rounded-xl hover:bg-primary-container transition-all flex items-center gap-2 font-bold cursor-pointer shadow-sm">
                    <span class="material-symbols-outlined text-base">save</span>
                    Save Profile Changes
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
