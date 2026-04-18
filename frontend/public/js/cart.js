function setCartBadge(count) {
  const badge = document.getElementById('cart-count');
  if (!badge) {
    return;
  }

  if (count > 0) {
    badge.textContent = count;
    badge.classList.remove('hidden');
    badge.classList.add('flex');
  } else {
    badge.classList.add('hidden');
    badge.classList.remove('flex');
  }
}

async function updateCartBadge() {
  if (window.MART_API.isAuthenticated()) {
    try {
      const items = await window.MART_API.fetchCart();
      const totalQuantity = items.reduce((sum, item) => sum + item.quantity, 0);
      setCartBadge(totalQuantity);
      return items;
    } catch (error) {
      setCartBadge(0);
      return [];
    }
  }

  setCartBadge(window.MART_GUEST_CART.count());
  return [];
}

async function addToCart(productId) {
  if (window.MART_API.isAuthenticated()) {
    try {
      await window.MART_API.addToCart(productId, 1);
      await updateCartBadge();
      window.showToast('Item added to cart.');
    } catch (error) {
      window.showToast(error.message || 'Unable to add item to cart.', true);
    }
    return;
  }

  if (typeof window.MART_PRODUCTS_CACHE !== 'undefined') {
    const product = window.MART_PRODUCTS_CACHE.find((p) => p.id === productId);
    if (product) {
      window.MART_GUEST_CART.add(product);
      updateCartBadge();
      window.showToast('Item added to cart.');
      return;
    }
  }

  window.showToast('Please log in to add items to cart.', true);
  window.MART_API.redirectToLogin();
}

document.addEventListener('DOMContentLoaded', () => {
  updateCartBadge();
});

window.updateCartBadge = updateCartBadge;
window.addToCart = addToCart;
