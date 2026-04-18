document.addEventListener('DOMContentLoaded', () => {
  if (window.MART_API.isAuthenticated()) {
    window.location.href = window.MART_API.consumeRedirect(window.MART_CONFIG.routes.shop);
    return;
  }

  const form = document.getElementById('register-form');
  const message = document.getElementById('auth-message');
  const submitButton = document.getElementById('register-submit');

  form.addEventListener('submit', async (event) => {
    event.preventDefault();

    submitButton.disabled = true;
    submitButton.textContent = 'Creating account...';
    message.classList.add('hidden');

    const formData = new FormData(form);

    try {
      const response = await window.MART_API.register({
        name: formData.get('name'),
        email: formData.get('email'),
        password: formData.get('password'),
        password_confirmation: formData.get('password_confirmation'),
      });

      window.MART_API.storeAuth(response.token, response.user);
      if (typeof window.updateAuthUi === 'function') {
        window.updateAuthUi();
      }
      await window.updateCartBadge();
      window.showToast('Account created successfully.');
      window.location.href = window.MART_API.consumeRedirect(window.MART_CONFIG.routes.shop);
    } catch (error) {
      message.textContent = error.message || 'Unable to create account.';
      message.classList.remove('hidden');
    } finally {
      submitButton.disabled = false;
      submitButton.textContent = 'Register';
    }
  });
});
