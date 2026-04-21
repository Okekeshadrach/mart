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

function findCachedProduct(productId) {
  if (!Array.isArray(window.MART_PRODUCTS_CACHE)) {
    return null;
  }

  return window.MART_PRODUCTS_CACHE.find((product) => product.id === productId) || null;
}

async function addToCart(productId, options = {}) {
  const cachedProduct = findCachedProduct(productId);

  if (cachedProduct && cachedProduct.inStock === false) {
    window.showToast('This product is currently out of stock.', true);
    return;
  }

  const selectedImage = options.selectedImage || options.image || cachedProduct?.image || null;

  if (window.MART_API.isAuthenticated()) {
    try {
      await window.MART_API.addToCart(productId, 1, { selectedImage });
      await updateCartBadge();
      window.showToast('Item added to cart.');
    } catch (error) {
      window.showToast(error.message || 'Unable to add item to cart.', true);
    }
    return;
  }

  if (cachedProduct) {
    window.MART_GUEST_CART.add(cachedProduct, { selectedImage });
    updateCartBadge();
    window.showToast('Item added to cart.');
    return;
  }

  window.showToast('Please log in to add items to cart.', true);
  window.MART_API.redirectToLogin();
}

document.addEventListener('DOMContentLoaded', () => {
  updateCartBadge();
});

window.updateCartBadge = updateCartBadge;
window.addToCart = addToCart;
