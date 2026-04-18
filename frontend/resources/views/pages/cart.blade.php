@extends('layouts.app')

@section('title', 'Cart - MART')
@section('page_title_template', 'Cart — {siteName}')

@section('content')
<main id="cart-main" class="max-w-7xl mx-auto px-4 sm:px-6 py-8 flex-grow"></main>
@endsection

@push('scripts')
<script src="{{ asset('js/cart-page.js') }}"></script>
@endpush
