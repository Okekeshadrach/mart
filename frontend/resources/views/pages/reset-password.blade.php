@extends('layouts.app')

@section('title', 'Reset Password — MART')

@section('content')
<main class="flex-grow flex items-center justify-center px-4 py-16">
  <div class="w-full max-w-md">
    <div class="p-8 rounded-xl bg-white shadow-sm">
      <h1 class="text-2xl font-bold mb-2 text-center">Reset Password</h1>
      <p class="text-sm text-gray-500 text-center mb-6">Enter your new password below.</p>

      <form id="reset-form" class="space-y-4">
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">New Password</label>
          <input id="reset-password" type="password" required minlength="8" class="w-full px-4 py-2.5 rounded-lg bg-gray-100 border-none outline-none text-sm focus:ring-2 focus:ring-accent">
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Confirm Password</label>
          <input id="reset-password-confirm" type="password" required minlength="8" class="w-full px-4 py-2.5 rounded-lg bg-gray-100 border-none outline-none text-sm focus:ring-2 focus:ring-accent">
        </div>
        <button type="submit" id="reset-btn" class="w-full py-3 rounded-xl bg-gray-900 text-white font-semibold hover:opacity-90 transition-opacity">Reset Password</button>
      </form>
    </div>
  </div>
</main>
@endsection

@push('scripts')
<script>
document.getElementById('reset-form').addEventListener('submit', async (e) => {
  e.preventDefault();
  const password = document.getElementById('reset-password').value;
  const passwordConfirm = document.getElementById('reset-password-confirm').value;

  if (password !== passwordConfirm) {
    window.showToast('Passwords do not match.', true);
    return;
  }

  const params = new URLSearchParams(window.location.search);
  const token = params.get('token');
  const email = params.get('email');

  if (!token || !email) {
    window.showToast('Invalid reset link.', true);
    return;
  }

  const btn = document.getElementById('reset-btn');
  btn.disabled = true;
  btn.textContent = 'Resetting...';

  try {
    await window.MART_API.request('/reset-password', {
      method: 'POST',
      body: { token, email, password, password_confirmation: passwordConfirm },
    });
    window.showToast('Password reset! Redirecting to login...');
    setTimeout(() => { window.location.href = window.MART_CONFIG.routes.login; }, 1500);
  } catch (error) {
    window.showToast(error.message || 'Unable to reset password.', true);
    btn.disabled = false;
    btn.textContent = 'Reset Password';
  }
});
</script>
@endpush
