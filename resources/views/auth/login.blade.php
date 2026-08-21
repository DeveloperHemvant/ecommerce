<x-layouts.auth title="Sign In" subtitle="Welcome back to Sonakshi Fashion Hub">
    @if(session('info'))
        <div class="mb-4 p-3.5 bg-amber-50 border border-amber-200 text-amber-900 text-xs rounded-xl flex items-center gap-2">
            <span class="material-symbols-outlined text-base">info</span>
            <span>{{ session('info') }}</span>
        </div>
    @endif

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

    <!-- Test Customer Credentials helper -->
    <div class="mb-5 p-3 bg-cream-silk border border-muted-gold/40 rounded-xl text-xs text-charcoal-text">
        <p class="font-bold text-heritage-burgundy mb-0.5">Customer Demo Account</p>
        <p class="font-data-tabular">Email: <strong>customer@gmail.com</strong> / Pass: <strong>12345678</strong></p>
    </div>

    <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="email">
                Email or Mobile Number
            </label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-lg">mail</span>
                <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl pl-10 pr-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors @error('email') border-error @enderror"
                    id="email" name="email" value="{{ old('email', 'customer@gmail.com') }}" placeholder="customer@gmail.com" required type="text" autocomplete="username" autofocus />
            </div>
        </div>

        <div>
            <div class="flex justify-between items-center mb-1.5">
                <label class="block font-label-caps text-xs text-on-surface-variant uppercase font-semibold" for="password">
                    Password
                </label>
                <a href="{{ route('password.request') }}" class="font-body-md text-xs text-heritage-burgundy hover:underline">
                    Forgot Password?
                </a>
            </div>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-lg">lock</span>
                <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl pl-10 pr-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors @error('password') border-error @enderror"
                    id="password" name="password" value="12345678" placeholder="••••••••" required type="password" autocomplete="current-password" />
            </div>
        </div>

        <div class="flex items-center">
            <input class="h-4 w-4 rounded border-border-subtle text-heritage-burgundy focus:ring-heritage-burgundy bg-transparent cursor-pointer"
                id="remember" name="remember" type="checkbox" checked />
            <label class="ml-2.5 font-body-md text-xs text-on-surface-variant cursor-pointer" for="remember">
                Keep me signed in
            </label>
        </div>

        <button type="submit"
            class="w-full bg-heritage-burgundy text-white font-label-caps text-xs uppercase tracking-wider py-4 rounded-xl hover:bg-primary-container transition-all flex items-center justify-center gap-2 shadow-sm font-bold cursor-pointer">
            Sign In to Account
            <span class="material-symbols-outlined text-base">arrow_forward</span>
        </button>

        <div class="relative my-4 text-center">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-border-subtle"></div></div>
            <span class="relative bg-surface-container-lowest px-3 text-xs font-label-caps text-on-surface-variant uppercase">or</span>
        </div>

        <div class="text-center font-body-md text-xs text-on-surface-variant">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-bold text-heritage-burgundy hover:underline ml-1">
                Create an Account
            </a>
        </div>
    </form>
</x-layouts.auth>
