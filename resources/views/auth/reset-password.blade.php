<x-layouts.auth title="Reset Password" subtitle="Choose a new password for your account">
    @if($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-error text-xs rounded-xl flex items-center gap-2">
            <span class="material-symbols-outlined text-base">error</span>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}" />

        <div>
            <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="email">
                Email Address
            </label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-lg">mail</span>
                <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl pl-10 pr-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors @error('email') border-error @enderror"
                    id="email" name="email" value="{{ old('email', $email) }}" placeholder="you@example.com" required type="email" autocomplete="username" autofocus />
            </div>
        </div>

        <div>
            <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="password">
                New Password
            </label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-lg">lock</span>
                <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl pl-10 pr-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors @error('password') border-error @enderror"
                    id="password" name="password" required type="password" autocomplete="new-password" placeholder="••••••••" />
            </div>
        </div>

        <div>
            <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="password_confirmation">
                Confirm New Password
            </label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-lg">lock</span>
                <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl pl-10 pr-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                    id="password_confirmation" name="password_confirmation" required type="password" autocomplete="new-password" placeholder="••••••••" />
            </div>
        </div>

        <button type="submit"
            class="w-full bg-heritage-burgundy text-white font-label-caps text-xs uppercase tracking-wider py-4 rounded-xl hover:bg-primary-container transition-all flex items-center justify-center gap-2 shadow-sm font-bold cursor-pointer">
            Reset Password
            <span class="material-symbols-outlined text-base">arrow_forward</span>
        </button>
    </form>
</x-layouts.auth>
