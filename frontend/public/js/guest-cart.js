const GUEST_CART_KEY = 'mart-guest-cart';

function getGuestCart() {
  try {
    return JSON.parse(localStorage.getItem(GUEST_CART_KEY) || '[]');
  } catch (e) {
    return [];
  }
}

function saveGuestCart(items) {
  localStorage.setItem(GUEST_CART_KEY, JSON.stringify(items));
}

function clearGuestCart() {
  localStorage.removeItem(GUEST_CART_KEY);
}

function addToGuestCart(product) {
  const cart = getGuestCart();
  const existing = cart.find((item) => item.productId === product.id);
  if (existing) {
    existing.quantity++;
  } else {
    cart.push({
      productId: product.id,
      name: product.name,
      slug: product.slug,
      price: product.price,
      image: product.image,
      category: product.category,
      quantity: 1,
    });
  }
  saveGuestCart(cart);
}

function getGuestCartCount() {
  return getGuestCart().reduce((sum, item) => sum + item.quantity, 0);
}

window.MART_GUEST_CART = {
  get: getGuestCart,
  save: saveGuestCart,
  clear: clearGuestCart,
  add: addToGuestCart,
  count: getGuestCartCount,
};
