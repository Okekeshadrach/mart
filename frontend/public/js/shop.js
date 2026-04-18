let categories = [];
let selectedCategories = [];
let minRating = 0;

function toggleSearch() {
  const form = document.getElementById('search-form');
  const button = document.getElementById('search-btn');

  if (form.classList.contains('hidden')) {
    form.classList.remove('hidden');
    form.classList.add('flex');
    button.classList.add('hidden');
    document.getElementById('search-input').focus();
  } else {
    form.classList.add('hidden');
    form.classList.remove('flex');
    button.classList.remove('hidden');
  }
}

function updateShopUrl() {
  const url = new URL(window.location.href);
  const search = window.searchQuery || '';

  if (search) {
    url.searchParams.set('search', search);
  } else {
    url.searchParams.delete('search');
  }

  window.history.replaceState({}, '', url.toString());
}

function updatePageTitle() {
  const title = document.getElementById('page-title');
  title.textContent = window.searchQuery ? `Results for "${window.searchQuery}"` : 'All Products';
}

function handleSearch(event) {
  event.preventDefault();
  window.searchQuery = document.getElementById('search-input').value.trim();
  updateShopUrl();
  updatePageTitle();
  loadProducts();
}

function renderFilters(containerId) {
  const container = document.getElementById(containerId);
  if (!container) {
    return;
  }

  let html = '<div class="space-y-6"><div><h3 class="font-semibold text-sm mb-3">Categories</h3><div class="space-y-2 text-sm text-gray-500">';

  categories.forEach((category) => {
    const checked = selectedCategories.includes(category.slug) ? 'checked' : '';
    html += `<label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" ${checked} onchange="toggleCat('${window.escapeHtml(category.slug)}')" class="rounded accent-[hsl(20,100%,54%)]"> ${window.escapeHtml(category.name)}</label>`;
  });

  html += '</div></div><div><h3 class="font-semibold text-sm mb-3">Rating</h3><div class="space-y-2 text-sm text-gray-500">';

  [4, 3].forEach((rating) => {
    const checked = minRating === rating ? 'checked' : '';
    html += `<label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" ${checked} onchange="toggleRating(${rating})" class="rounded accent-[hsl(20,100%,54%)]"> ${'&#9733;'.repeat(rating)}${'&#9734;'.repeat(5 - rating)} & up</label>`;
  });

  html += '</div></div></div>';
  container.innerHTML = html;
}

function toggleCat(slug) {
  if (selectedCategories.includes(slug)) {
    selectedCategories = selectedCategories.filter((value) => value !== slug);
  } else {
    selectedCategories.push(slug);
  }

  loadProducts();
}

function toggleRating(rating) {
  minRating = minRating === rating ? 0 : rating;
  loadProducts();
}

function productStars(rating, size = 'w-3 h-3') {
  const rounded = Math.round(rating || 0);
  return Array.from({ length: 5 }, (_, index) => index < rounded
    ? `<svg class="${size} fill-[hsl(20,100%,54%)] text-[hsl(20,100%,54%)]" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>`
    : `<svg class="${size} text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>`).join('');
}

function renderProducts(products) {
  const grid = document.getElementById('product-grid');
  const noProducts = document.getElementById('no-products');

  if (!products.length) {
    grid.innerHTML = '';
    noProducts.classList.remove('hidden');
    return;
  }

  noProducts.classList.add('hidden');

  grid.innerHTML = products.map((product) => {
    const discount = product.originalPrice
      ? `<span class="text-xs line-through text-gray-400 ml-1">$${product.originalPrice.toFixed(2)}</span>`
      : '';

    return `<div class="product-card rounded-xl bg-white shadow-sm transition-all duration-300 overflow-hidden group"><a href="/product/${encodeURIComponent(product.slug)}"><div class="aspect-square overflow-hidden bg-gray-100"><img src="${window.escapeHtml(product.image)}" alt="${window.escapeHtml(product.name)}" class="product-img w-full h-full object-cover transition-transform duration-500" loading="lazy"></div></a><div class="p-4"><p class="text-xs text-gray-500 mb-1">${window.escapeHtml(product.category)}</p><a href="/product/${encodeURIComponent(product.slug)}" class="font-semibold text-sm hover:text-[hsl(20,100%,54%)] transition-colors line-clamp-1">${window.escapeHtml(product.name)}</a><div class="flex items-center gap-1 mt-1.5">${productStars(product.rating)}<span class="text-xs text-gray-400 ml-1">(${product.reviewCount})</span></div><div class="flex items-center justify-between mt-3"><div class="flex items-center"><span class="font-bold">$${product.price.toFixed(2)}</span>${discount}</div><button onclick="addToCart(${product.id})" class="p-2 rounded-lg bg-gray-900 text-white hover:bg-[hsl(20,100%,54%)] transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg></button></div></div></div>`;
  }).join('');
}

async function loadCategories() {
  categories = await window.MART_API.fetchCategories();
  renderFilters('filters-desktop');
  renderFilters('filters-mobile');
}

async function loadProducts() {
  const grid = document.getElementById('product-grid');
  const sort = document.getElementById('sort-select').value;

  grid.innerHTML = '<p class="col-span-full text-center text-gray-500 py-16">Loading products...</p>';

  try {
    const products = await window.MART_API.fetchProducts({
      category: selectedCategories.join(','),
      min_rating: minRating || '',
      search: window.searchQuery || '',
      sort,
    });

    renderProducts(products);
    window.MART_PRODUCTS_CACHE = products;
  } catch (error) {
    grid.innerHTML = '<p class="col-span-full text-center text-red-500 py-16">Unable to load products right now.</p>';
    window.showToast(error.message || 'Unable to load products.', true);
  }
}

window.searchQuery = new URLSearchParams(window.location.search).get('search') || '';

document.addEventListener('DOMContentLoaded', async () => {
  const searchInput = document.getElementById('search-input');
  if (searchInput) {
    searchInput.value = window.searchQuery;
  }

  updatePageTitle();

  try {
    await loadCategories();
  } catch (error) {
    window.showToast('Unable to load categories.', true);
  }

  await loadProducts();
});

window.toggleSearch = toggleSearch;
window.handleSearch = handleSearch;
window.toggleCat = toggleCat;
window.toggleRating = toggleRating;
window.reloadProducts = loadProducts;
