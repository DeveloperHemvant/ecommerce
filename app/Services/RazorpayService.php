<?php

namespace App\Services;

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class RazorpayService
{
    protected Api $api;

    public function __construct()
    {
        $this->api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
    }

    /**
     * Create a Razorpay order and return its raw attributes (includes 'id').
     */
    public function createOrder(int $amountInPaise, string $receipt): array
    {
        $order = $this->api->order->create([
            'receipt' => $receipt,
            'amount' => $amountInPaise,
            'currency' => 'INR',
            'payment_capture' => 1,
        ]);

        return $order->toArray();
    }

    /**
     * Verify a Razorpay Standard Checkout payment signature.
     */
    public function verifySignature(array $attributes): bool
    {
        try {
            $this->api->utility->verifyPaymentSignature($attributes);

            return true;
        } catch (SignatureVerificationError) {
            return false;
        }
    }

    /**
     * Verify the X-Razorpay-Signature header on an incoming webhook request
     * against the raw request body, using the dashboard-configured webhook secret.
     */
    public function verifyWebhookSignature(string $rawPayload, string $signature): bool
    {
        $secret = config('services.razorpay.webhook_secret');

        if (empty($secret)) {
            return false;
        }

        try {
            $this->api->utility->verifyWebhookSignature($rawPayload, $signature, $secret);

            return true;
        } catch (SignatureVerificationError) {
            return false;
        }
    }
}
