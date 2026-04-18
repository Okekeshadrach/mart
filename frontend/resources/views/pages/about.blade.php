@extends('layouts.app')

@section('title', 'About - MART')
@section('page_title_template', 'About — {siteName}')

@section('content')
<main class="flex-grow">
  <section class="max-w-7xl mx-auto px-6 py-16 text-center">
    <h1 data-site-setting="aboutTitle" class="text-3xl sm:text-4xl font-bold mb-4">About MART</h1>
    <p data-site-setting="aboutDescription" class="text-gray-500 max-w-2xl mx-auto text-lg leading-relaxed">We believe shopping should be simple, enjoyable, and accessible. MART brings together thousands of quality products across every category from electronics and fashion to home essentials and beauty all in one place.</p>
  </section>

  <section class="max-w-7xl mx-auto px-6 pb-16">
    <div id="about-features" class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="p-8 rounded-xl bg-white shadow-sm text-center">
        <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
          <svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <h3 class="font-bold mb-2">Curated Selection</h3>
        <p class="text-sm text-gray-500">Every product is hand-picked for quality, value, and customer satisfaction.</p>
      </div>
      <div class="p-8 rounded-xl bg-white shadow-sm text-center">
        <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
          <svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
        </div>
        <h3 class="font-bold mb-2">Fast Shipping</h3>
        <p class="text-sm text-gray-500">Free shipping on every order with express delivery options available.</p>
      </div>
      <div class="p-8 rounded-xl bg-white shadow-sm text-center">
        <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
          <svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <h3 class="font-bold mb-2">Secure Payments</h3>
        <p class="text-sm text-gray-500">Your transactions are protected with industry-leading encryption.</p>
      </div>
    </div>
  </section>

  <section class="bg-gray-900 text-white py-16">
    <div id="about-stats" class="max-w-7xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
      <div><p class="text-3xl font-bold text-accent">10K+</p><p class="text-sm text-gray-400 mt-1">Products</p></div>
      <div><p class="text-3xl font-bold text-accent">50K+</p><p class="text-sm text-gray-400 mt-1">Happy Customers</p></div>
      <div><p class="text-3xl font-bold text-accent">99%</p><p class="text-sm text-gray-400 mt-1">Satisfaction</p></div>
      <div><p class="text-3xl font-bold text-accent">24/7</p><p class="text-sm text-gray-400 mt-1">Support</p></div>
    </div>
  </section>
</main>
@endsection
