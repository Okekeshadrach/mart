let checkoutItems = [];

function checkoutPromptHtml() {
  return `<div class="text-center py-16"><h1 class="text-2xl font-bold mb-3">Login Required</h1><p class="text-gray-500 mb-6">Please log in to place an order.</p><div class="flex items-center justify-center gap-3"><a href="${window.MART_CONFIG.routes.login}?redirect=${encodeURIComponent(window.location.pathname)}" class="px-6 py-3 rounded-xl bg-gray-900 text-white font-semibold hover:opacity-90">Login</a><a href="${window.MART_CONFIG.routes.register}" class="px-6 py-3 rounded-xl bg-gray-100 text-gray-900 font-semibold hover:bg-gray-200 transition-colors">Register</a></div></div>`;
}

function setCheckoutTotals(items) {
  const total = items.reduce((sum, item) => sum + item.lineTotal, 0);
  document.getElementById('subtotal').textContent = `$${total.toFixed(2)}`;
  document.getElementById('total').textContent = `$${total.toFixed(2)}`;
  document.getElementById('order-items').innerHTML = items.map((item) => `<div class="flex items-center gap-3"><div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0"><img src="${window.escapeHtml(item.image)}" class="w-full h-full object-cover"></div><div class="flex-grow min-w-0"><p class="text-sm font-medium truncate">${window.escapeHtml(item.name)}</p><p class="text-xs text-gray-500">Qty: ${item.quantity}</p></div><span class="text-sm font-bold">$${item.lineTotal.toFixed(2)}</span></div>`).join('');
}

async function loadCheckout() {
  const main = document.querySelector('main');
  const settings = await window.loadSiteSettings();
  if (!window.MART_API.isAuthenticated()) {
    main.innerHTML = checkoutPromptHtml();
    return;
  }

  try {
    checkoutItems = await window.MART_API.fetchCart();
  } catch (error) {
    main.innerHTML = `<div class="text-center py-16 text-red-500">${window.escapeHtml(error.message || 'Unable to load checkout right now.')}</div>`;
    return;
  }

  if (!checkoutItems.length) {
    document.getElementById('order-items').innerHTML = '<p class="text-sm text-gray-500">Your cart is empty.</p>';
    document.getElementById('subtotal').textContent = '$0.00';
    document.getElementById('total').textContent = '$0.00';
    document.getElementById('checkout-shipping-label').textContent = settings?.shippingSummaryLabel || 'Free';
    return;
  }

  const user = window.MART_API.getStoredUser();
  if (user) {
    const emailInput = document.querySelector('input[name="email"]');
    if (emailInput && !emailInput.value) {
      emailInput.value = user.email || '';
    }
  }

  setCheckoutTotals(checkoutItems);
  document.getElementById('checkout-shipping-label').textContent = settings?.shippingSummaryLabel || 'Free';
}

async function placeOrder() {
  const form = document.getElementById('checkout-form');
  if (!form.checkValidity()) {
    form.reportValidity();
    return;
  }

  if (!window.MART_API.isAuthenticated()) {
    window.MART_API.redirectToLogin(window.location.pathname);
    return;
  }

  if (!checkoutItems.length) {
    window.showToast('Your cart is empty.', true);
    return;
  }

  const formData = new FormData(form);
  const payload = Object.fromEntries(formData.entries());
  payload.payment = document.querySelector('input[name="payment"]:checked')?.value || 'card';

  try {
    await window.MART_API.placeOrder(payload);
    await window.updateCartBadge();
    window.showToast('Order placed successfully.');
    window.location.href = window.MART_CONFIG.routes.orders;
  } catch (error) {
    if (error.status === 401) {
      window.MART_API.redirectToLogin(window.location.pathname);
      return;
    }

    window.showToast(error.message || 'Unable to place order.', true);
  }
}

document.addEventListener('DOMContentLoaded', loadCheckout);

window.placeOrder = placeOrder;
