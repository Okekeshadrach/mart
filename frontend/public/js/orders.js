function ordersLoginPrompt() {
  return `<div class="text-center py-16"><h1 class="text-2xl font-bold mb-3">Login Required</h1><p class="text-gray-500 mb-6">Please log in to view your order history.</p><div class="flex items-center justify-center gap-3"><a href="${window.MART_CONFIG.routes.login}?redirect=${encodeURIComponent(window.location.pathname)}" class="px-6 py-3 rounded-xl bg-gray-900 text-white font-semibold hover:opacity-90">Login</a><a href="${window.MART_CONFIG.routes.register}" class="px-6 py-3 rounded-xl bg-gray-100 text-gray-900 font-semibold hover:bg-gray-200 transition-colors">Register</a></div></div>`;
}

async function renderOrders() {
  const main = document.getElementById('orders-main');

  if (!window.MART_API.isAuthenticated()) {
    main.innerHTML = ordersLoginPrompt();
    return;
  }

  main.innerHTML = '<div class="text-center py-16 text-gray-500">Loading your orders...</div>';

  try {
    const orders = await window.MART_API.fetchOrders();

    if (!orders.length) {
      main.innerHTML = '<div class="text-center py-16"><h1 class="text-2xl font-bold mb-3">No orders yet</h1><p class="text-gray-500 mb-6">Once you place an order, it will appear here.</p><a href="/shop" class="inline-block px-6 py-3 rounded-xl bg-gray-900 text-white font-semibold hover:opacity-90">Start Shopping</a></div>';
      return;
    }

    main.innerHTML = `<h1 class="text-2xl sm:text-3xl font-bold mb-8">My Orders</h1><div class="space-y-6">${orders.map((order) => `<section class="bg-white rounded-2xl shadow-sm p-6"><div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5"><div><p class="text-sm text-gray-500">Order #${order.id}</p><h2 class="font-bold text-lg mt-1">$${order.total.toFixed(2)}</h2></div><div class="text-sm"><span class="inline-flex px-3 py-1 rounded-full bg-gray-100 text-gray-700 font-medium capitalize">${window.escapeHtml(order.status)}</span><p class="text-gray-400 mt-2">${new Date(order.createdAt).toLocaleString()}</p></div></div><div class="space-y-3 mb-5">${order.items.map((item) => `<div class="flex items-center gap-3"><div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">${item.productImage ? `<img src="${window.escapeHtml(item.productImage)}" alt="${window.escapeHtml(item.productName || '')}" class="w-full h-full object-cover">` : ''}</div><div class="flex-grow min-w-0"><p class="text-sm font-medium truncate">${window.escapeHtml(item.productName || 'Product')}</p><p class="text-xs text-gray-500">Qty: ${item.quantity}</p></div><span class="text-sm font-bold">$${item.lineTotal.toFixed(2)}</span></div>`).join('')}</div><div class="border-t border-gray-200 pt-4 text-sm text-gray-500 space-y-1"><p><span class="font-medium text-gray-700">Payment:</span> ${window.escapeHtml(order.paymentMethod)}</p><p><span class="font-medium text-gray-700">Ship To:</span> ${window.escapeHtml([order.shippingAddress.first_name, order.shippingAddress.last_name].filter(Boolean).join(' '))}, ${window.escapeHtml(order.shippingAddress.address)}, ${window.escapeHtml(order.shippingAddress.city)}, ${window.escapeHtml(order.shippingAddress.country)}</p></div></section>`).join('')}</div>`;
  } catch (error) {
    if (error.status === 401) {
      main.innerHTML = ordersLoginPrompt();
      return;
    }

    main.innerHTML = `<div class="text-center py-16 text-red-500">${window.escapeHtml(error.message || 'Unable to load orders.')}</div>`;
  }
}

document.addEventListener('DOMContentLoaded', renderOrders);
