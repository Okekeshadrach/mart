document.addEventListener('DOMContentLoaded', () => {
  if (window.MART_API.isAuthenticated()) {
    window.location.href = window.MART_API.consumeRedirect(window.MART_CONFIG.routes.shop);
    return;
  }

  const form = document.getElementById('login-form');
  const message = document.getElementById('auth-message');
  const submitButton = document.getElementById('login-submit');

  form.addEventListener('submit', async (event) => {
    event.preventDefault();

    submitButton.disabled = true;
    submitButton.textContent = 'Logging in...';
    message.classList.add('hidden');

    const formData = new FormData(form);

    try {
      const response = await window.MART_API.login({
        email: formData.get('email'),
        password: formData.get('password'),
      });

      window.MART_API.storeAuth(response.token, response.user);
      if (typeof window.updateAuthUi === 'function') {
        window.updateAuthUi();
      }
      await window.updateCartBadge();
      window.showToast('Login successful.');
      window.location.href = window.MART_API.consumeRedirect(window.MART_CONFIG.routes.shop);
    } catch (error) {
      message.textContent = error.message || 'Unable to log in.';
      message.classList.remove('hidden');
    } finally {
      submitButton.disabled = false;
      submitButton.textContent = 'Login';
    }
  });
});
