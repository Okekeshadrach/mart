@extends('layouts.app')

@section('title', 'MART - Your One-Stop Multi-Product Store')
@section('page_title_template', '{siteName} — {siteTagline}')
@section('meta_description_template', '{metaDescription}')

@section('search')
<div id="search-container">
  <button onclick="toggleSearch()" id="search-btn" class="text-gray-500 hover:text-gray-900 transition-colors">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
  </button>
  <form onsubmit="handleSearch(event)" id="search-form" class="hidden items-center">
    <input id="search-input" placeholder="Search products..." class="text-sm px-4 py-2 rounded-full bg-gray-100 border-none outline-none w-40 sm:w-56">
    <button type="button" onclick="toggleSearch()" class="ml-2 text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
  </form>
</div>
@endsection

@section('content')
<main class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-10 flex-grow">
  <div class="flex items-center justify-between mb-8">
    <h1 id="page-title" class="text-2xl sm:text-3xl font-bold">All Products</h1>
    <div class="flex items-center gap-3">
      <select id="sort-select" onchange="renderProducts()" class="text-sm px-3 py-2 rounded-lg bg-gray-100 border-none outline-none cursor-pointer">
        <option value="featured">Sort: Featured</option>
        <option value="price-asc">Price: Low to High</option>
        <option value="price-desc">Price: High to Low</option>
        <option value="newest">Newest</option>
      </select>
      <button class="md:hidden p-2 rounded-lg bg-gray-100 text-gray-500" onclick="document.getElementById('mobile-filters').classList.toggle('hidden')">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 21V14M4 10V3M12 21V12M12 8V3M20 21V16M20 12V3M1 14h6M9 8h6M17 16h6"/></svg>
      </button>
    </div>
  </div>

  <div class="flex gap-8">
    <aside class="hidden md:block w-56 flex-shrink-0">
      <div id="filters-desktop"></div>
    </aside>

    <div id="mobile-filters" class="hidden fixed inset-0 z-40 bg-[#fafafa] p-6 md:hidden overflow-y-auto">
      <div class="flex justify-between items-center mb-6">
        <h2 class="font-bold text-lg">Filters</h2>
        <button onclick="document.getElementById('mobile-filters').classList.add('hidden')"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
      </div>
      <div id="filters-mobile"></div>
    </div>

    <div class="flex-grow">
      <div id="product-grid" class="grid grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5"></div>
      <p id="no-products" class="hidden text-gray-500 text-center py-16">No products found.</p>
    </div>
  </div>
</main>
@endsection

@push('scripts')
<script src="{{ asset('js/shop.js') }}"></script>
@endpush
