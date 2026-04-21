const MART_TOKEN_KEY = 'mart-auth-token';
const MART_USER_KEY = 'mart-auth-user';
const MART_REDIRECT_KEY = 'mart-post-auth-redirect';

function martConfig() {
  return window.MART_CONFIG || { apiBaseUrl: '', routes: {} };
}

function escapeHtml(value) {
  return String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}

function showToast(message, isError = false) {
  const toast = document.createElement('div');
  toast.className = `fixed bottom-4 right-4 z-50 px-4 py-3 rounded-xl text-sm shadow-lg transition-opacity ${isError ? 'bg-red-600 text-white' : 'bg-gray-900 text-white'}`;
  toast.textContent = message;
  document.body.appendChild(toast);
  setTimeout(() => {
    toast.style.opacity = '0';
    setTimeout(() => toast.remove(), 300);
  }, 2500);
}

function getStoredUser() {
  try {
    return JSON.parse(localStorage.getItem(MART_USER_KEY) || 'null');
  } catch (error) {
    return null;
  }
}

function getToken() {
  return localStorage.getItem(MART_TOKEN_KEY);
}

function isAuthenticated() {
  return Boolean(getToken());
}

function getSiteCurrency() {
  return window.MART_SITE_SETTINGS?.siteCurrency || 'USD';
}

function formatCurrency(amount, currency = getSiteCurrency()) {
  const value = Number(amount ?? 0);

  try {
    return new Intl.NumberFormat(undefined, {
      style: 'currency',
      currency,
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    }).format(value);
  } catch (error) {
    return `${currency} ${value.toFixed(2)}`;
  }
}

function storeAuth(token, user) {
  localStorage.setItem(MART_TOKEN_KEY, token);
  localStorage.setItem(MART_USER_KEY, JSON.stringify(user));
}

function clearAuth() {
  localStorage.removeItem(MART_TOKEN_KEY);
  localStorage.removeItem(MART_USER_KEY);
}

function rememberRedirect(path) {
  sessionStorage.setItem(MART_REDIRECT_KEY, path);
}

function normalizeRedirect(path, fallback) {
  if (!path) {
    return fallback;
  }

  if (path.startsWith('/') && !path.startsWith('//')) {
    return path;
  }

  try {
    const url = new URL(path, window.location.origin);
    if (url.origin === window.location.origin) {
      return `${url.pathname}${url.search}${url.hash}`;
    }
  } catch (error) {
    return fallback;
  }

  return fallback;
}

function consumeRedirect(fallback) {
  const redirect = new URLSearchParams(window.location.search).get('redirect')
    || sessionStorage.getItem(MART_REDIRECT_KEY)
    || fallback;

  sessionStorage.removeItem(MART_REDIRECT_KEY);

  return normalizeRedirect(redirect, fallback);
}

function redirectToLogin(path = window.location.pathname + window.location.search) {
  rememberRedirect(path);
  window.location.href = `${martConfig().routes.login}?redirect=${encodeURIComponent(path)}`;
}

function apiUrl(path, query = null) {
  const baseUrl = martConfig().apiBaseUrl.replace(/\/+$/, '');
  const normalizedPath = path.startsWith('/') ? path : `/${path}`;
  const url = new URL(`${baseUrl}${normalizedPath}`);

  if (query) {
    Object.entries(query).forEach(([key, value]) => {
      if (value !== null && value !== undefined && value !== '') {
        url.searchParams.set(key, value);
      }
    });
  }

  return url.toString();
}

async function request(path, options = {}) {
  const headers = new Headers(options.headers || {});
  headers.set('Accept', 'application/json');

  const token = getToken();
  if (token) {
    headers.set('Authorization', `Bearer ${token}`);
  }

  const fetchOptions = {
    method: options.method || 'GET',
    headers,
  };

  if (options.body) {
    if (options.body instanceof FormData) {
      fetchOptions.body = options.body;
    } else {
      headers.set('Content-Type', 'application/json');
      fetchOptions.body = JSON.stringify(options.body);
    }
  }

  const controller = new AbortController();
  fetchOptions.signal = controller.signal;
  const timeout = setTimeout(() => controller.abort(), 15000);

  let response;
  try {
    response = await fetch(apiUrl(path, options.query), fetchOptions);
  } finally {
    clearTimeout(timeout);
  }

  if (response.status === 204) {
    return null;
  }

  let payload = {};
  try {
    payload = await response.json();
  } catch (error) {
    payload = {};
  }

  if (!response.ok) {
    const apiError = new Error(payload.message || 'Something went wrong.');
    apiError.status = response.status;
    apiError.payload = payload;

    if (response.status === 401) {
      clearAuth();
      if (typeof window.updateAuthUi === 'function') {
        window.updateAuthUi();
      }
    }

    throw apiError;
  }

  return payload;
}

const MART_API = {
  escapeHtml,
  showToast,
  getStoredUser,
  getToken,
  isAuthenticated,
  getSiteCurrency,
  formatCurrency,
  storeAuth,
  clearAuth,
  redirectToLogin,
  consumeRedirect,
  request,
  async login(data) {
    return request('/login', { method: 'POST', body: data });
  },
  async register(data) {
    return request('/register', { method: 'POST', body: data });
  },
  async logout() {
    return request('/logout', { method: 'POST' });
  },
  async fetchProducts(query = {}) {
    const payload = await request('/products', { query });
    return payload.data || [];
  },
  async fetchSiteSettings(force = false) {
    if (!force) {
      const cached = window.MART_SITE_SETTINGS;
      if (cached) {
        window.MART_SITE_SETTINGS = cached;
        return cached;
      }
    }

    const payload = await request('/settings/site');
    const settings = payload.data || payload;

    window.MART_SITE_SETTINGS = settings;

    return settings;
  },
  async fetchProduct(slug) {
    const payload = await request(`/products/${encodeURIComponent(slug)}`);
    return payload.data || payload;
  },
  async fetchCategories() {
    const payload = await request('/categories');
    return payload.data || [];
  },
  async fetchCart() {
    const payload = await request('/cart');
    return payload.data || [];
  },
  async addToCart(productId, quantity = 1, options = {}) {
    const payload = await request('/cart', {
      method: 'POST',
      body: {
        product_id: productId,
        quantity,
        selected_image: options.selectedImage || options.image || null,
      },
    });

    return payload.data || payload;
  },
  async updateCartItem(cartItemId, quantity) {
    const payload = await request(`/cart/${cartItemId}`, {
      method: 'PATCH',
      body: { quantity },
    });

    return payload.data || payload;
  },
  async removeCartItem(cartItemId) {
    return request(`/cart/${cartItemId}`, { method: 'DELETE' });
  },
  async mergeGuestCart() {
    const guestCart = window.MART_GUEST_CART?.get?.() || [];

    if (!guestCart.length || !isAuthenticated()) {
      return { merged: 0, failed: 0 };
    }

    const payload = guestCart.map((item) => ({
      productId: item.productId,
      quantity: item.quantity,
      selectedImage: item.selectedImage || item.image || null,
    }));

    const response = await request('/cart/merge', {
      method: 'POST',
      body: { items: payload },
    });

    const failedItems = response.failedItems || [];
    const failedIds = new Set(failedItems.map((item) => Number(item.productId)));

    if (failedIds.size) {
      window.MART_GUEST_CART.save(
        guestCart.filter((item) => failedIds.has(Number(item.productId))),
      );
    } else {
      window.MART_GUEST_CART.clear();
    }

    return {
      merged: response.mergedItemCount || 0,
      failed: failedItems.length,
      failedItems,
    };
  },
  async placeOrder(data) {
    const payload = await request('/orders', {
      method: 'POST',
      body: data,
    });

    return payload.data || payload;
  },
  async fetchOrders() {
    const payload = await request('/orders');
    return payload.data || [];
  },
  async createReview(productId, data) {
    const payload = await request(`/products/${productId}/reviews`, {
      method: 'POST',
      body: data,
    });

    return payload.data || payload;
  },
};

window.MART_API = MART_API;
window.escapeHtml = escapeHtml;
window.formatCurrency = formatCurrency;
window.showToast = showToast;
