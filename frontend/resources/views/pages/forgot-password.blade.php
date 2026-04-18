@extends('layouts.app')

@section('title', 'Forgot Password — MART')

@section('content')
<main class="flex-grow flex items-center justify-center px-4 py-16">
  <div class="w-full max-w-md">
    <div class="p-8 rounded-xl bg-white shadow-sm">
      <h1 class="text-2xl font-bold mb-2 text-center">Forgot Password</h1>
      <p class="text-sm text-gray-500 text-center mb-6">Enter your email and we'll send you a reset link.</p>

      <div id="forgot-success" class="hidden p-4 rounded-lg bg-green-50 text-green-700 text-sm mb-4"></div>

      <form id="forgot-form" class="space-y-4">
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Email</label>
          <input id="forgot-email" type="email" required class="w-full px-4 py-2.5 rounded-lg bg-gray-100 border-none outline-none text-sm focus:ring-2 focus:ring-accent">
        </div>
        <button type="submit" id="forgot-btn" class="w-full py-3 rounded-xl bg-gray-900 text-white font-semibold hover:opacity-90 transition-opacity">Send Reset Link</button>
      </form>

      <p class="text-sm text-center text-gray-500 mt-6">
        Remember your password? <a href="{{ route('login') }}" class="text-accent font-medium">Login</a>
      </p>
    </div>
  </div>
</main>
@endsection

@push('scripts')
<script>
document.getElementById('forgot-form').addEventListener('submit', async (e) => {
  e.preventDefault();
  const btn = document.getElementById('forgot-btn');
  const success = document.getElementById('forgot-success');
  btn.disabled = true;
  btn.textContent = 'Sending...';

  try {
    const result = await window.MART_API.request('/forgot-password', {
      method: 'POST',
      body: { email: document.getElementById('forgot-email').value },
    });
    success.textContent = result.message || 'If an account exists, a reset link has been sent.';
    success.classList.remove('hidden');
    document.getElementById('forgot-form').reset();
  } catch (error) {
    window.showToast(error.message || 'Something went wrong.', true);
  } finally {
    btn.disabled = false;
    btn.textContent = 'Send Reset Link';
  }
});
</script>
@endpush
