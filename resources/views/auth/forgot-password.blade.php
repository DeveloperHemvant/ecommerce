<x-layouts.auth title="Forgot Password" subtitle="We'll email you a link to reset your password">
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

    <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="email">
                Email Address
            </label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-lg">mail</span>
                <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl pl-10 pr-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors @error('email') border-error @enderror"
                    id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required type="email" autocomplete="username" autofocus />
            </div>
        </div>

        <button type="submit"
            class="w-full bg-heritage-burgundy text-white font-label-caps text-xs uppercase tracking-wider py-4 rounded-xl hover:bg-primary-container transition-all flex items-center justify-center gap-2 shadow-sm font-bold cursor-pointer">
            Send Reset Link
            <span class="material-symbols-outlined text-base">arrow_forward</span>
        </button>

        <div class="text-center font-body-md text-xs text-on-surface-variant">
            Remembered your password?
            <a href="{{ route('login') }}" class="font-bold text-heritage-burgundy hover:underline ml-1">
                Sign In
            </a>
        </div>
    </form>
</x-layouts.auth>
