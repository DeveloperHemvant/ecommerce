<x-layouts.checkout title="Checkout: Secure Payment - Sonakshi Fashion Hub" currentStep="3">
    <div class="max-w-lg mx-auto text-center py-16">
        <div id="rzp-status" class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-8 md:p-10 shadow-xs space-y-5">
            <div class="w-14 h-14 mx-auto rounded-full bg-cream-silk border border-muted-gold/30 flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl text-heritage-burgundy animate-pulse">lock</span>
            </div>
            <h2 class="font-headline-md text-xl text-heritage-burgundy">Redirecting to Secure Payment</h2>
            <p class="font-body-md text-xs text-on-surface-variant">
                You're paying <strong>₹{{ number_format($total) }}</strong> via {{ $paymentMethod }}. A secure Razorpay payment window will open automatically.
            </p>

            @if(session('error'))
                <div class="p-3 bg-red-50 border border-red-200 text-error text-xs rounded-xl flex items-center gap-2 text-left">
                    <span class="material-symbols-outlined text-base">error</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <button id="rzp-open-btn" type="button"
                class="w-full bg-heritage-burgundy text-white font-label-caps text-xs uppercase tracking-wider py-4 rounded-xl hover:bg-primary-container transition-all flex items-center justify-center gap-2 font-bold cursor-pointer shadow-lg">
                <span class="material-symbols-outlined text-lg">lock</span>
                <span>Pay Now</span>
            </button>

            <a href="{{ route('checkout.review') }}" class="block text-xs font-label-caps text-on-surface-variant hover:text-heritage-burgundy transition-colors">
                Cancel and return to order review
            </a>
        </div>
    </div>

    <form id="rzp-verify-form" action="{{ route('checkout.payment.verify') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="razorpay_payment_id" id="rzp_payment_id" />
        <input type="hidden" name="razorpay_order_id" id="rzp_order_id" />
        <input type="hidden" name="razorpay_signature" id="rzp_signature" />
    </form>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        (function () {
            var options = {
                key: @json($razorpayKey),
                amount: @json($amountInPaise),
                currency: 'INR',
                name: 'Sonakshi Fashion Hub',
                description: 'Order Payment',
                order_id: @json($razorpayOrderId),
                prefill: {
                    name: @json($shipping['customer_name']),
                    email: @json($shipping['customer_email']),
                    contact: @json($shipping['customer_phone']),
                },
                theme: { color: '#600018' },
                handler: function (response) {
                    document.getElementById('rzp_payment_id').value = response.razorpay_payment_id;
                    document.getElementById('rzp_order_id').value = response.razorpay_order_id;
                    document.getElementById('rzp_signature').value = response.razorpay_signature;
                    document.getElementById('rzp-verify-form').submit();
                },
                modal: {
                    ondismiss: function () {
                        // Customer closed the widget without paying — let them retry manually.
                    }
                }
            };

            var rzp = new Razorpay(options);
            var openBtn = document.getElementById('rzp-open-btn');

            openBtn.addEventListener('click', function () {
                rzp.open();
            });

            // Auto-open on load for a smoother handoff from "Confirm & Place Order".
            rzp.open();
        })();
    </script>
</x-layouts.checkout>
