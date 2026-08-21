<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Notifications\OrderConfirmedNotification;
use App\Services\OrderFulfillmentService;
use App\Services\RazorpayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Server-to-server backstop for the checkout flow: if Razorpay captures a
 * payment but the customer's browser never completes the verifyPayment()
 * round trip (closed tab, dropped connection), this webhook still turns it
 * into an order. finalizePayment() is idempotent, so this safely no-ops if
 * the browser already finished the order first.
 */
class RazorpayWebhookController extends Controller
{
    public function __construct(protected RazorpayService $razorpay, protected OrderFulfillmentService $fulfillment) {}

    public function handle(Request $request): JsonResponse
    {
        $signature = $request->header('X-Razorpay-Signature', '');
        $rawPayload = $request->getContent();

        if (! $signature || ! $this->razorpay->verifyWebhookSignature($rawPayload, $signature)) {
            Log::warning('Razorpay webhook: signature verification failed.');

            return response()->json(['status' => 'invalid signature'], 400);
        }

        $payload = json_decode($rawPayload, true) ?? [];
        $event = $payload['event'] ?? null;

        if ($event !== 'payment.captured') {
            // Acknowledge everything else — we only act on capture events.
            return response()->json(['status' => 'ignored']);
        }

        $paymentEntity = $payload['payload']['payment']['entity'] ?? null;
        $razorpayOrderId = $paymentEntity['order_id'] ?? null;
        $paymentId = $paymentEntity['id'] ?? null;

        if (! $razorpayOrderId || ! $paymentId) {
            return response()->json(['status' => 'malformed payload'], 400);
        }

        try {
            $result = $this->fulfillment->finalizePayment($razorpayOrderId, $paymentId);
        } catch (\RuntimeException $e) {
            // No pending checkout and no existing order — nothing we can reconcile.
            Log::warning('Razorpay webhook: could not finalize payment.', [
                'razorpay_order_id' => $razorpayOrderId,
                'payment_id' => $paymentId,
                'message' => $e->getMessage(),
            ]);

            return response()->json(['status' => 'no matching checkout'], 200);
        }

        if ($result['created']) {
            Mail::to($result['order']->customer_email)->send(new OrderConfirmation($result['order']));
            $result['order']->user?->notify(new OrderConfirmedNotification($result['order']));
        }

        return response()->json(['status' => 'ok']);
    }
}
