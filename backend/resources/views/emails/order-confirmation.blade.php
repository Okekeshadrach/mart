@extends('emails.layout', ['subject' => 'Order Confirmed #' . $order->id])

@section('content')
<h1>Order Confirmed!</h1>
<p>Hi {{ $order->user->name }}, thanks for your order. We've received it and will begin processing shortly.</p>

<div class="detail-grid">
  <p class="label">Order Number</p>
  <p class="value">#{{ $order->id }}</p>
  <p class="label">Payment Method</p>
  <p class="value">{{ ucfirst(str_replace('_', ' ', $order->payment_method->value)) }}</p>
</div>

<table class="table">
  <thead>
    <tr>
      <th>Product</th>
      <th style="text-align: center;">Qty</th>
      <th style="text-align: right;">Price</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($order->items as $item)
    <tr>
      <td>{{ $item->product->name }}</td>
      <td style="text-align: center;">{{ $item->quantity }}</td>
      <td style="text-align: right;">${{ number_format($item->price * $item->quantity, 2) }}</td>
    </tr>
    @endforeach
    <tr class="total-row">
      <td colspan="2">Total</td>
      <td style="text-align: right;">${{ number_format($order->total, 2) }}</td>
    </tr>
  </tbody>
</table>

<hr class="divider">

<div class="detail-grid">
  <p class="label">Shipping To</p>
  <p class="value">
    {{ $order->shipping_address['first_name'] }} {{ $order->shipping_address['last_name'] }}<br>
    {{ $order->shipping_address['address'] }}<br>
    {{ $order->shipping_address['city'] }}, {{ $order->shipping_address['zip'] }}<br>
    {{ $order->shipping_address['country'] }}
  </p>
</div>

<p style="text-align: center; margin: 28px 0;">
  <a href="{{ config('app.frontend_url') }}/orders" class="btn">View Your Orders</a>
</p>
@endsection
