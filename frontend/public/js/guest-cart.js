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

function normalizeGuestCartImage(product, options = {}) {
  return options.selectedImage || options.image || product.selectedImage || product.image || null;
}

function addToGuestCart(product, options = {}) {
  const cart = getGuestCart();
  const selectedImage = normalizeGuestCartImage(product, options);
  const existing = cart.find((item) => item.productId === product.id && (item.selectedImage || item.image) === selectedImage);

  if (existing) {
    existing.quantity++;
    existing.image = selectedImage;
    existing.selectedImage = selectedImage;
  } else {
    cart.push({
      productId: product.id,
      name: product.name,
      slug: product.slug,
      price: product.price,
      image: selectedImage,
      selectedImage,
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
