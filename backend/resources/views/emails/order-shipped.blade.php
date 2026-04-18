@extends('emails.layout', ['subject' => 'Order #' . $order->id . ' Has Been Shipped'])

@section('content')
<h1>Your Order Has Shipped! 🚚</h1>
<p>Hi {{ $order->user->name }}, great news — your order <strong>#{{ $order->id }}</strong> is on its way.</p>

<div class="detail-grid">
  <p class="label">Shipping To</p>
  <p class="value">
    {{ $order->shipping_address['first_name'] }} {{ $order->shipping_address['last_name'] }}<br>
    {{ $order->shipping_address['address'] }}<br>
    {{ $order->shipping_address['city'] }}, {{ $order->shipping_address['zip'] }}<br>
    {{ $order->shipping_address['country'] }}
  </p>
</div>

<table class="table">
  <thead>
    <tr>
      <th>Product</th>
      <th style="text-align: center;">Qty</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($order->items as $item)
    <tr>
      <td>{{ $item->product->name }}</td>
      <td style="text-align: center;">{{ $item->quantity }}</td>
    </tr>
    @endforeach
  </tbody>
</table>

<p style="text-align: center; margin: 28px 0;">
  <a href="{{ config('app.frontend_url') }}/orders" class="btn">Track Your Order</a>
</p>
@endsection
