<x-layouts.auth title="Create Account" subtitle="Join Sonakshi Fashion Hub for VIP lookbooks & fast checkout">
    @if($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-error text-xs rounded-xl flex items-center gap-2">
            <span class="material-symbols-outlined text-base">error</span>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="name">
                Full Name
            </label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-lg">person</span>
                <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl pl-10 pr-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors @error('name') border-error @enderror"
                    id="name" name="name" value="{{ old('name') }}" placeholder="Priya Sharma" required type="text" />
            </div>
        </div>

        <div>
            <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="email">
                Email Address
            </label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-lg">mail</span>
                <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl pl-10 pr-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors @error('email') border-error @enderror"
                    id="email" name="email" value="{{ old('email') }}" placeholder="priya.sharma@example.com" required type="email" />
            </div>
        </div>

        <div>
            <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="phone">
                Mobile Number
            </label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-lg">call</span>
                <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl pl-10 pr-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors @error('phone') border-error @enderror"
                    id="phone" name="phone" value="{{ old('phone') }}" placeholder="+91 98765 43210" required type="tel" />
            </div>
        </div>

        <div>
            <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="password">
                Password (min. 6 characters)
            </label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-lg">lock</span>
                <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl pl-10 pr-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors @error('password') border-error @enderror"
                    id="password" name="password" placeholder="••••••••" required type="password" />
            </div>
        </div>

        <div class="flex items-start pt-1">
            <input class="h-4 w-4 rounded border-border-subtle text-heritage-burgundy focus:ring-heritage-burgundy bg-transparent mt-0.5 cursor-pointer"
                id="terms" name="terms" type="checkbox" required checked />
            <label class="ml-2.5 font-body-md text-xs text-on-surface-variant leading-tight cursor-pointer" for="terms">
                I agree to the <a href="#" class="text-heritage-burgundy underline">Terms of Service</a> &amp; <a href="#" class="text-heritage-burgundy underline">Privacy Policy</a>
            </label>
        </div>

        <button type="submit"
            class="w-full bg-heritage-burgundy text-white font-label-caps text-xs uppercase tracking-wider py-4 rounded-xl hover:bg-primary-container transition-all flex items-center justify-center gap-2 shadow-sm font-bold cursor-pointer mt-2">
            Create Customer Account
            <span class="material-symbols-outlined text-base">arrow_forward</span>
        </button>

        <div class="text-center font-body-md text-xs text-on-surface-variant pt-2">
            Already have an account?
            <a href="{{ route('login') }}" class="font-bold text-heritage-burgundy hover:underline ml-1">
                Sign In
            </a>
        </div>
    </form>
</x-layouts.auth>
