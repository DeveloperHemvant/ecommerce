<x-mail::message>
# Your order is now {{ ucfirst($order->status) }}

Hi {{ $order->customer_name }}, here's an update on order **{{ $order->order_number }}**.

**Status:** {{ ucfirst($order->status) }}
@if($order->courier_name)
**Courier:** {{ $order->courier_name }}
@endif
@if($order->tracking_number)
**Tracking Number:** {{ $order->tracking_number }}
@endif

<x-mail::button :url="route('track.order', ['order' => $order->order_number])">
Track Your Order
</x-mail::button>

Thanks for shopping with us,<br>
{{ config('app.name') }}
</x-mail::message>
