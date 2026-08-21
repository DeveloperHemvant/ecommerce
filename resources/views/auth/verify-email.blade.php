<x-layouts.auth title="Verify Your Email" subtitle="One quick step before you're all set">
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 text-xs rounded-xl flex items-center gap-2">
            <span class="material-symbols-outlined text-base">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="text-center space-y-5">
        <div class="w-14 h-14 mx-auto rounded-full bg-cream-silk border border-muted-gold/30 flex items-center justify-center">
            <span class="material-symbols-outlined text-2xl text-heritage-burgundy">mark_email_unread</span>
        </div>

        <p class="font-body-md text-sm text-charcoal-text">
            We've sent a verification link to <strong>{{ auth()->user()->email }}</strong>.
            Click it to confirm your email address.
        </p>

        <form action="{{ route('verification.send') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full bg-heritage-burgundy text-white font-label-caps text-xs uppercase tracking-wider py-4 rounded-xl hover:bg-primary-container transition-all flex items-center justify-center gap-2 shadow-sm font-bold cursor-pointer">
                Resend Verification Email
            </button>
        </form>

        <a href="{{ route('collections') }}" class="block font-body-md text-xs text-on-surface-variant hover:text-heritage-burgundy transition-colors">
            Continue browsing without verifying
        </a>
    </div>
</x-layouts.auth>
