@extends('layouts.app')

@section('title', 'Product - MART')
@section('page_title_template', 'Product — {siteName}')

@section('content')
<main id="product-main" class="max-w-7xl mx-auto px-4 sm:px-6 py-8 flex-grow"></main>
@endsection

@push('scripts')
<script>
  window.MART_PAGE.productSlug = @json($slug);
</script>
<script src="{{ asset('js/product.js') }}"></script>
@endpush
