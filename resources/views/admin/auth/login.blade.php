<x-layouts.auth title="Admin Portal Sign In" subtitle="Sonakshi Fashion Hub Backoffice Suite" :isAdmin="true">
    @if(session('error'))
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-error text-xs rounded-xl flex items-center gap-2">
            <span class="material-symbols-outlined text-base">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 text-xs rounded-xl flex items-center gap-2">
            <span class="material-symbols-outlined text-base">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-error text-xs rounded-xl flex items-center gap-2">
            <span class="material-symbols-outlined text-base">error</span>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <!-- Quick Credentials Helper Banner -->
    <div class="mb-6 p-3.5 bg-cream-silk border border-muted-gold/40 rounded-xl text-xs text-charcoal-text">
        <p class="font-bold text-heritage-burgundy mb-1 flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">key</span>
            Default Admin Credentials
        </p>
        <p class="font-data-tabular">Email: <strong class="text-heritage-burgundy">admin@gmail.com</strong></p>
        <p class="font-data-tabular">Password: <strong class="text-heritage-burgundy">12345678</strong></p>
    </div>

    <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="email">
                Admin Email Address
            </label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-lg">admin_panel_settings</span>
                <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl pl-10 pr-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors @error('email') border-error @enderror"
                    id="email" name="email" value="{{ old('email', 'admin@gmail.com') }}" required type="email" autocomplete="email" autofocus />
            </div>
        </div>

        <div>
            <div class="flex justify-between items-center mb-1.5">
                <label class="block font-label-caps text-xs text-on-surface-variant uppercase font-semibold" for="password">
                    Admin Password
                </label>
            </div>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-lg">lock</span>
                <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl pl-10 pr-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors @error('password') border-error @enderror"
                    id="password" name="password" value="12345678" required type="password" autocomplete="current-password" />
            </div>
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center cursor-pointer">
                <input class="h-4 w-4 rounded border-border-subtle text-heritage-burgundy focus:ring-heritage-burgundy bg-transparent"
                    id="remember" name="remember" type="checkbox" checked />
                <span class="ml-2 font-body-md text-xs text-on-surface-variant">Remember session</span>
            </label>
            <span class="text-xs font-label-caps text-muted-gold font-semibold uppercase">Role: Admin</span>
        </div>

        <button type="submit"
            class="w-full bg-heritage-burgundy text-white font-label-caps text-xs uppercase tracking-wider py-4 rounded-xl hover:bg-primary-container transition-all flex items-center justify-center gap-2 shadow-sm font-bold cursor-pointer">
            Sign In to Admin Dashboard
            <span class="material-symbols-outlined text-base">login</span>
        </button>

        <div class="pt-4 border-t border-border-subtle/80 text-center">
            <p class="text-xs font-body-md text-on-surface-variant/75">
                Authorized Personnel Only &bull; Secured with PostgreSQL/MySQL DB &amp; Bcrypt
            </p>
        </div>
    </form>
</x-layouts.auth>
