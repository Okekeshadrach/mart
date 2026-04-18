@extends('layouts.app')

@section('title', 'Checkout - MART')
@section('page_title_template', 'Checkout — {siteName}')

@section('content')
<main class="max-w-7xl mx-auto px-4 sm:px-6 py-8 flex-grow">
  <h1 class="text-2xl sm:text-3xl font-bold mb-8">Checkout</h1>
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
      <div class="p-6 rounded-xl bg-white shadow-sm">
        <h2 class="font-bold text-lg mb-4">Shipping Information</h2>
        <form id="checkout-form" class="space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div><label class="text-sm font-medium text-gray-700 mb-1 block">First Name</label><input name="first_name" required class="w-full px-4 py-2.5 rounded-lg bg-gray-100 border-none outline-none text-sm focus:ring-2 focus:ring-accent"></div>
            <div><label class="text-sm font-medium text-gray-700 mb-1 block">Last Name</label><input name="last_name" required class="w-full px-4 py-2.5 rounded-lg bg-gray-100 border-none outline-none text-sm focus:ring-2 focus:ring-accent"></div>
          </div>
          <div><label class="text-sm font-medium text-gray-700 mb-1 block">Email</label><input name="email" type="email" required class="w-full px-4 py-2.5 rounded-lg bg-gray-100 border-none outline-none text-sm focus:ring-2 focus:ring-accent"></div>
          <div><label class="text-sm font-medium text-gray-700 mb-1 block">Address</label><input name="address" required class="w-full px-4 py-2.5 rounded-lg bg-gray-100 border-none outline-none text-sm focus:ring-2 focus:ring-accent"></div>
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            <div><label class="text-sm font-medium text-gray-700 mb-1 block">City</label><input name="city" required class="w-full px-4 py-2.5 rounded-lg bg-gray-100 border-none outline-none text-sm focus:ring-2 focus:ring-accent"></div>
            <div><label class="text-sm font-medium text-gray-700 mb-1 block">ZIP</label><input name="zip" required class="w-full px-4 py-2.5 rounded-lg bg-gray-100 border-none outline-none text-sm focus:ring-2 focus:ring-accent"></div>
            <div class="col-span-2 sm:col-span-1"><label class="text-sm font-medium text-gray-700 mb-1 block">Country</label><input name="country" required class="w-full px-4 py-2.5 rounded-lg bg-gray-100 border-none outline-none text-sm focus:ring-2 focus:ring-accent"></div>
          </div>
        </form>
      </div>
      <div class="p-6 rounded-xl bg-white shadow-sm">
        <h2 class="font-bold text-lg mb-4">Payment Method</h2>
        <div class="space-y-3">
          <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 cursor-pointer hover:border-accent transition-colors"><input type="radio" name="payment" value="card" checked class="accent-[hsl(20,100%,54%)]"><span class="text-sm font-medium">Credit / Debit Card</span></label>
          <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 cursor-pointer hover:border-accent transition-colors"><input type="radio" name="payment" value="paypal" class="accent-[hsl(20,100%,54%)]"><span class="text-sm font-medium">PayPal</span></label>
          <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 cursor-pointer hover:border-accent transition-colors"><input type="radio" name="payment" value="cod" class="accent-[hsl(20,100%,54%)]"><span class="text-sm font-medium">Cash on Delivery</span></label>
        </div>
      </div>
    </div>
    <div class="lg:col-span-1">
      <div class="p-6 rounded-xl bg-white shadow-sm sticky top-24">
        <h2 class="font-bold text-lg mb-4">Order Summary</h2>
        <div id="order-items" class="space-y-3 mb-4"></div>
        <div class="border-t border-gray-200 pt-4 space-y-2 text-sm mb-4">
          <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span id="subtotal">$0.00</span></div>
          <div class="flex justify-between"><span class="text-gray-500">Shipping</span><span id="checkout-shipping-label" class="text-accent font-medium">Free</span></div>
        </div>
        <div class="border-t border-gray-200 pt-4 flex justify-between font-bold text-lg mb-6"><span>Total</span><span id="total">$0.00</span></div>
        <button onclick="placeOrder()" class="block text-center w-full py-3 rounded-xl bg-gray-900 text-white font-semibold hover:opacity-90 transition-opacity">Place Order</button>
      </div>
    </div>
  </div>
</main>
@endsection

@push('scripts')
<script src="{{ asset('js/checkout.js') }}"></script>
@endpush
