@extends('layouts.app')

@section('title', 'Login - MART')
@section('page_title_template', 'Login — {siteName}')

@section('content')
<main class="max-w-md mx-auto px-4 sm:px-6 py-10 flex-grow w-full">
  <div class="bg-white rounded-2xl shadow-sm p-6 sm:p-8">
    <h1 class="text-2xl font-bold mb-2">Login</h1>
    <p class="text-sm text-gray-500 mb-6">Access your MART account to manage your cart and orders.</p>
    <div id="auth-message" class="hidden mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"></div>
    <form id="login-form" class="space-y-4">
      <div>
        <label class="text-sm font-medium text-gray-700 mb-1 block">Email</label>
        <input name="email" type="email" required class="w-full px-4 py-2.5 rounded-lg bg-gray-100 border-none outline-none text-sm focus:ring-2 focus:ring-accent">
      </div>
      <div>
        <label class="text-sm font-medium text-gray-700 mb-1 block">Password</label>
        <input name="password" type="password" required class="w-full px-4 py-2.5 rounded-lg bg-gray-100 border-none outline-none text-sm focus:ring-2 focus:ring-accent">
      </div>
      <button id="login-submit" type="submit" class="w-full py-3 rounded-xl bg-gray-900 text-white font-semibold hover:opacity-90 transition-opacity">Login</button>
    </form>
    <p class="text-sm text-gray-500 mt-4"><a href="{{ route('forgot-password') }}" class="text-accent font-medium">Forgot your password?</a></p>
    <p class="text-sm text-gray-500 mt-2">No account yet? <a href="{{ route('register') }}" class="text-accent font-medium">Register here</a></p>
  </div>
</main>
@endsection

@push('scripts')
<script src="{{ asset('js/login.js') }}"></script>
@endpush
