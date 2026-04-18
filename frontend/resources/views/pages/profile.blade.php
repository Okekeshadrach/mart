@extends('layouts.app')

@section('title', 'Profile — MART')

@section('content')
<main class="flex-grow max-w-2xl mx-auto px-4 sm:px-6 py-10 w-full">
  <h1 class="text-2xl sm:text-3xl font-bold mb-8">My Profile</h1>

  <div id="profile-login-prompt" class="hidden"></div>

  <div id="profile-content" class="hidden space-y-8">
    <div class="p-6 rounded-xl bg-white shadow-sm">
      <h2 class="font-bold text-lg mb-4">Account Details</h2>
      <form id="profile-form" class="space-y-4">
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Name</label>
          <input id="profile-name" name="name" required class="w-full px-4 py-2.5 rounded-lg bg-gray-100 border-none outline-none text-sm focus:ring-2 focus:ring-accent">
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Email</label>
          <input id="profile-email" type="email" disabled class="w-full px-4 py-2.5 rounded-lg bg-gray-50 border-none outline-none text-sm text-gray-400 cursor-not-allowed">
        </div>
        <button type="submit" class="w-full py-3 rounded-xl bg-gray-900 text-white font-semibold hover:opacity-90 transition-opacity">Save Changes</button>
      </form>
    </div>

    <div class="p-6 rounded-xl bg-white shadow-sm">
      <h2 class="font-bold text-lg mb-4">Change Password</h2>
      <form id="password-form" class="space-y-4">
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Current Password</label>
          <input id="current-password" name="current_password" type="password" required class="w-full px-4 py-2.5 rounded-lg bg-gray-100 border-none outline-none text-sm focus:ring-2 focus:ring-accent">
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">New Password</label>
          <input id="new-password" name="password" type="password" required minlength="8" class="w-full px-4 py-2.5 rounded-lg bg-gray-100 border-none outline-none text-sm focus:ring-2 focus:ring-accent">
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Confirm New Password</label>
          <input id="confirm-password" name="password_confirmation" type="password" required minlength="8" class="w-full px-4 py-2.5 rounded-lg bg-gray-100 border-none outline-none text-sm focus:ring-2 focus:ring-accent">
        </div>
        <button type="submit" class="w-full py-3 rounded-xl bg-gray-900 text-white font-semibold hover:opacity-90 transition-opacity">Update Password</button>
      </form>
    </div>
  </div>
</main>
@endsection

@push('scripts')
<script src="{{ asset('js/profile.js') }}"></script>
@endpush
