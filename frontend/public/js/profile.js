function showLoginPrompt() {
  const prompt = document.getElementById('profile-login-prompt');
  const content = document.getElementById('profile-content');
  content.classList.add('hidden');
  prompt.classList.remove('hidden');
  prompt.innerHTML = `<div class="text-center py-16"><h2 class="text-2xl font-bold mb-3">Login Required</h2><p class="text-gray-500 mb-6">Please log in to manage your profile.</p><div class="flex items-center justify-center gap-3"><a href="${window.MART_CONFIG.routes.login}?redirect=${encodeURIComponent('/profile')}" class="px-6 py-3 rounded-xl bg-gray-900 text-white font-semibold hover:opacity-90">Login</a><a href="${window.MART_CONFIG.routes.register}" class="px-6 py-3 rounded-xl bg-gray-100 text-gray-900 font-semibold hover:bg-gray-200 transition-colors">Register</a></div></div>`;
}

async function loadProfile() {
  if (!window.MART_API.isAuthenticated()) {
    showLoginPrompt();
    return;
  }

  const content = document.getElementById('profile-content');
  const prompt = document.getElementById('profile-login-prompt');
  prompt.classList.add('hidden');
  content.classList.remove('hidden');

  try {
    const payload = await window.MART_API.request('/profile');
    const user = payload.data || payload;
    document.getElementById('profile-name').value = user.name || '';
    document.getElementById('profile-email').value = user.email || '';
  } catch (error) {
    if (error.status === 401) {
      showLoginPrompt();
      return;
    }
    window.showToast(error.message || 'Unable to load profile.', true);
  }
}

async function handleProfileUpdate(event) {
  event.preventDefault();
  const name = document.getElementById('profile-name').value.trim();

  try {
    const payload = await window.MART_API.request('/profile', {
      method: 'PUT',
      body: { name },
    });
    const user = payload.data || payload;
    window.MART_API.storeAuth(window.MART_API.getToken(), user);
    if (typeof window.updateAuthUi === 'function') {
      window.updateAuthUi();
    }
    window.showToast('Profile updated.');
  } catch (error) {
    const msg = error.payload?.errors?.name?.[0] || error.message || 'Unable to update profile.';
    window.showToast(msg, true);
  }
}

async function handlePasswordUpdate(event) {
  event.preventDefault();
  const current_password = document.getElementById('current-password').value;
  const password = document.getElementById('new-password').value;
  const password_confirmation = document.getElementById('confirm-password').value;

  if (password !== password_confirmation) {
    window.showToast('Passwords do not match.', true);
    return;
  }

  try {
    await window.MART_API.request('/profile/password', {
      method: 'PUT',
      body: { current_password, password, password_confirmation },
    });
    document.getElementById('password-form').reset();
    window.showToast('Password updated.');
  } catch (error) {
    const errors = error.payload?.errors;
    const msg = errors?.current_password?.[0] || errors?.password?.[0] || error.message || 'Unable to update password.';
    window.showToast(msg, true);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  loadProfile();
  document.getElementById('profile-form').addEventListener('submit', handleProfileUpdate);
  document.getElementById('password-form').addEventListener('submit', handlePasswordUpdate);
});
