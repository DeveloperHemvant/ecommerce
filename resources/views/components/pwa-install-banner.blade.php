<!-- PWA Install Floating Banner -->
<div id="pwaInstallBanner" class="fixed bottom-20 md:bottom-6 left-4 right-4 md:left-auto md:right-6 md:max-w-md bg-white/95 backdrop-blur-md border border-muted-gold/50 rounded-2xl p-4 shadow-[0_12px_32px_rgba(96,0,24,0.18)] z-50 transform translate-y-32 opacity-0 transition-all duration-500 pointer-events-none">
    <div class="flex items-center gap-3.5">
        <!-- App Icon -->
        <div class="w-12 h-12 rounded-xl overflow-hidden shadow-xs shrink-0 border border-muted-gold/30">
            <img src="/icons/icon-192x192.png" alt="Sonakshi App" class="w-full h-full object-cover" />
        </div>

        <!-- Text Description -->
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-1.5">
                <h4 class="font-headline-md text-sm text-heritage-burgundy font-bold">Install Sonakshi App</h4>
                <span class="bg-heritage-burgundy/10 text-heritage-burgundy text-[9px] font-label-caps uppercase font-bold px-1.5 py-0.2 rounded">Fast</span>
            </div>
            <p class="font-body-md text-[11px] text-on-surface-variant leading-tight mt-0.5">
                Instant order tracking, custom fit alerts &amp; seamless 1-tap checkout.
            </p>
        </div>

        <!-- Action & Dismiss Buttons -->
        <div class="flex items-center gap-1.5 shrink-0">
            <button id="pwaInstallBtn" type="button" class="bg-heritage-burgundy hover:bg-primary-container text-white px-3.5 py-2 rounded-xl font-label-caps text-[11px] uppercase font-bold transition-all shadow-xs cursor-pointer">
                Install
            </button>
            <button id="pwaDismissBtn" type="button" class="text-on-surface-variant hover:text-charcoal-text p-1.5 rounded-lg transition-colors cursor-pointer" title="Dismiss">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        </div>
    </div>
</div>

<!-- PWA Registration & Install Script -->
<script>
    // 1. Service Worker Registration
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then((reg) => console.log('Sonakshi PWA Service Worker Registered:', reg.scope))
                .catch((err) => console.error('Service Worker Registration Failed:', err));
        });
    }

    // 2. Custom Native Install Prompt
    let deferredInstallPrompt = null;
    const installBanner = document.getElementById('pwaInstallBanner');
    const installBtn = document.getElementById('pwaInstallBtn');
    const dismissBtn = document.getElementById('pwaDismissBtn');

    window.addEventListener('beforeinstallprompt', (e) => {
        // Prevent immediate Chrome mini-infobar
        e.preventDefault();
        deferredInstallPrompt = e;

        // Check if user dismissed recently (last 7 days)
        const dismissedAt = localStorage.getItem('sonakshi_pwa_dismissed');
        if (dismissedAt && (Date.now() - parseInt(dismissedAt)) < 7 * 24 * 60 * 60 * 1000) {
            return;
        }

        // Show royal install banner after 2 seconds delay
        setTimeout(() => {
            if (installBanner) {
                installBanner.classList.remove('translate-y-32', 'opacity-0', 'pointer-events-none');
                installBanner.classList.add('translate-y-0', 'opacity-100', 'pointer-events-auto');
            }
        }, 2000);
    });

    if (installBtn) {
        installBtn.addEventListener('click', async () => {
            if (!deferredInstallPrompt) return;

            // Trigger native install dialog
            deferredInstallPrompt.prompt();
            const { outcome } = await deferredInstallPrompt.userChoice;
            console.log('PWA Install Prompt outcome:', outcome);

            deferredInstallPrompt = null;
            hidePwaBanner();
        });
    }

    if (dismissBtn) {
        dismissBtn.addEventListener('click', () => {
            localStorage.setItem('sonakshi_pwa_dismissed', Date.now().toString());
            hidePwaBanner();
        });
    }

    function hidePwaBanner() {
        if (installBanner) {
            installBanner.classList.remove('translate-y-0', 'opacity-100', 'pointer-events-auto');
            installBanner.classList.add('translate-y-32', 'opacity-0', 'pointer-events-none');
        }
    }

    // Hide banner once app is installed
    window.addEventListener('appinstalled', () => {
        hidePwaBanner();
        console.log('Sonakshi PWA was installed successfully!');
    });
</script>
