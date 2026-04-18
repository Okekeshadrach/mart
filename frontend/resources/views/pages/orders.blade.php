@extends('layouts.app')

@section('title', 'My Orders - MART')
@section('page_title_template', 'My Orders — {siteName}')

@section('content')
<main id="orders-main" class="max-w-7xl mx-auto px-4 sm:px-6 py-8 flex-grow"></main>
@endsection

@push('scripts')
<script src="{{ asset('js/orders.js') }}"></script>
@endpush
