<x-mail::message>
# Thank you for your order, {{ $order->customer_name }}!

Your order **{{ $order->order_number }}** has been confirmed and is being prepared with care.

<x-mail::table>
| Item | Qty | Total |
| :--- | :-- | :---- |
@foreach($order->items as $item)
| {{ $item->product_name }}@if($item->size) ({{ $item->size }})@endif | {{ $item->quantity }} | ₹{{ number_format((float) $item->total) }} |
@endforeach
</x-mail::table>

**Subtotal:** ₹{{ number_format((float) $order->subtotal) }}
@if($order->discount > 0)
**Discount:** -₹{{ number_format((float) $order->discount) }}
@endif
**Total:** ₹{{ number_format((float) $order->total_amount) }}

**Shipping to:**
{{ $order->shipping_address }}, {{ $order->city }}, {{ $order->state }} {{ $order->postal_code }}, {{ $order->country }}

**Payment method:** {{ $order->payment_method }}

<x-mail::button :url="route('track.order', ['order' => $order->order_number])">
Track Your Order
</x-mail::button>

Thanks for shopping with us,<br>
{{ config('app.name') }}
</x-mail::message>
