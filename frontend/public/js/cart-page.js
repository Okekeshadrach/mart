let currentCartItems = [];

function guestCartToDisplay(guestItems) {
  return guestItems.map((item, index) => ({
    id: index,
    productId: item.productId,
    name: item.name,
    slug: item.slug,
    price: item.price,
    image: item.selectedImage || item.image,
    selectedImage: item.selectedImage || item.image,
    category: item.category,
    quantity: item.quantity,
    lineTotal: item.price * item.quantity,
    isGuest: true,
  }));
}

function renderCartHtml(items, shippingLabel) {
  if (!items.length) {
    return '<div class="text-center py-16"><svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg><h1 class="text-2xl font-bold mb-2">Your cart is empty</h1><p class="text-gray-500 mb-6">Looks like you haven\'t added anything yet.</p><a href="/shop" class="inline-block px-6 py-3 rounded-xl bg-gray-900 text-white font-semibold hover:opacity-90">Continue Shopping</a></div>';
  }

  const total = items.reduce((sum, item) => sum + item.lineTotal, 0);
  const isGuest = items[0]?.isGuest;
  const checkoutHref = isGuest
    ? `${window.MART_CONFIG.routes.login}?redirect=${encodeURIComponent('/cart')}`
    : window.MART_CONFIG.routes.checkout;
  const checkoutLabel = isGuest ? 'Login to Checkout' : 'Proceed to Checkout';

  return `<h1 class="text-2xl sm:text-3xl font-bold mb-8">Shopping Cart</h1><div class="grid grid-cols-1 lg:grid-cols-3 gap-8"><div class="lg:col-span-2 space-y-4">${items.map((item) => `<div class="flex gap-4 p-4 rounded-xl bg-white shadow-sm"><a href="/product/${encodeURIComponent(item.slug || '')}" class="w-24 h-24 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0"><img src="${window.escapeHtml(item.image)}" alt="${window.escapeHtml(item.name)}" class="w-full h-full object-cover"></a><div class="flex-grow min-w-0"><a href="/product/${encodeURIComponent(item.slug || '')}" class="font-semibold text-sm hover:text-accent transition-colors">${window.escapeHtml(item.name)}</a><p class="text-sm text-gray-500">${window.escapeHtml(item.category)}</p><p class="font-bold mt-1">${window.formatCurrency(item.price)}</p></div><div class="flex flex-col items-end justify-between"><button onclick="removeItem(${item.isGuest ? item.id : item.id}, ${!!item.isGuest})" class="text-gray-400 hover:text-red-500 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button><div class="flex items-center gap-2 bg-gray-100 rounded-lg"><button onclick="updateQty(${item.isGuest ? item.id : item.id}, -1, ${!!item.isGuest})" class="p-1.5 hover:text-accent transition-colors"><svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14"/></svg></button><span class="text-sm font-medium w-6 text-center">${item.quantity}</span><button onclick="updateQty(${item.isGuest ? item.id : item.id}, 1, ${!!item.isGuest})" class="p-1.5 hover:text-accent transition-colors"><svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg></button></div></div></div>`).join('')}</div><div class="lg:col-span-1"><div class="p-6 rounded-xl bg-white shadow-sm sticky top-24"><h2 class="font-bold text-lg mb-4">Order Summary</h2><div class="space-y-2 text-sm mb-4"><div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>${window.formatCurrency(total)}</span></div><div class="flex justify-between"><span class="text-gray-500">Shipping</span><span class="text-accent font-medium">${window.escapeHtml(shippingLabel)}</span></div></div><div class="border-t border-gray-200 pt-4 flex justify-between font-bold text-lg mb-6"><span>Total</span><span>${window.formatCurrency(total)}</span></div><a href="${checkoutHref}" class="block text-center w-full py-3 rounded-xl bg-gray-900 text-white font-semibold hover:opacity-90 transition-opacity">${checkoutLabel}</a></div></div></div>`;
}

async function renderCart() {
  const main = document.getElementById('cart-main');
  const settings = typeof window.loadSiteSettings === 'function' ? await window.loadSiteSettings() : null;
  const shippingLabel = settings?.shippingSummaryLabel || 'Free';

  if (window.MART_API.isAuthenticated()) {
    main.innerHTML = '<div class="text-center py-16 text-gray-500">Loading your cart...</div>';

    try {
      currentCartItems = await window.MART_API.fetchCart();
    } catch (error) {
      if (error.status === 401) {
        currentCartItems = guestCartToDisplay(window.MART_GUEST_CART.get());
        main.innerHTML = renderCartHtml(currentCartItems, shippingLabel);
        return;
      }
      main.innerHTML = '<div class="text-center py-16 text-red-500">Unable to load your cart right now.</div>';
      return;
    }

    main.innerHTML = renderCartHtml(currentCartItems, shippingLabel);
    return;
  }

  currentCartItems = guestCartToDisplay(window.MART_GUEST_CART.get());
  main.innerHTML = renderCartHtml(currentCartItems, shippingLabel);
}

async function removeItem(itemId, isGuest) {
  if (isGuest) {
    const cart = window.MART_GUEST_CART.get();
    cart.splice(itemId, 1);
    window.MART_GUEST_CART.save(cart);
    window.updateCartBadge();
    renderCart();
    return;
  }

  try {
    await window.MART_API.removeCartItem(itemId);
    await window.updateCartBadge();
    await renderCart();
  } catch (error) {
    window.showToast(error.message || 'Unable to remove item.', true);
  }
}

async function updateQty(itemId, delta, isGuest) {
  if (isGuest) {
    const cart = window.MART_GUEST_CART.get();
    if (cart[itemId]) {
      cart[itemId].quantity += delta;
      if (cart[itemId].quantity < 1) {
        cart.splice(itemId, 1);
      }
    }
    window.MART_GUEST_CART.save(cart);
    window.updateCartBadge();
    renderCart();
    return;
  }

  const item = currentCartItems.find((entry) => entry.id === itemId);
  if (!item) {
    return;
  }

  const nextQuantity = item.quantity + delta;

  try {
    if (nextQuantity < 1) {
      await window.MART_API.removeCartItem(itemId);
    } else {
      await window.MART_API.updateCartItem(itemId, nextQuantity);
    }
    await window.updateCartBadge();
    await renderCart();
  } catch (error) {
    window.showToast(error.message || 'Unable to update quantity.', true);
  }
}

document.addEventListener('DOMContentLoaded', renderCart);

window.removeItem = removeItem;
window.updateQty = updateQty;
