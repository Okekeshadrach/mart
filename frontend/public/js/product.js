function detailStars(rating, size = 'w-4 h-4') {
  const rounded = Math.round(rating || 0);
  return Array.from({ length: 5 }, (_, index) => index < rounded
    ? `<svg class="${size} fill-[hsl(20,100%,54%)] text-[hsl(20,100%,54%)]" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>`
    : `<svg class="${size} text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>`).join('');
}

async function renderProductPage() {
  const slug = window.MART_PAGE.productSlug;
  const main = document.getElementById('product-main');
  const settings = await window.loadSiteSettings();

  main.innerHTML = '<div class="text-center py-16 text-gray-500">Loading product...</div>';

  try {
    const product = await window.MART_API.fetchProduct(slug);
    const related = (await window.MART_API.fetchProducts({ category: product.categorySlug }))
      .filter((item) => item.slug !== product.slug)
      .slice(0, 4);

    document.title = `${product.name} - ${settings?.siteName || 'MART'}`;
    window.MART_PRODUCTS_CACHE = [product, ...related];

    const thumbs = product.images.length > 1
      ? `<div class="flex gap-3 mt-4">${product.images.map((image, index) => `<button onclick="selectImage(${index})" class="w-20 h-20 rounded-lg overflow-hidden border-2 transition-colors ${index === 0 ? 'border-accent' : 'border-gray-200'}"><img src="${window.escapeHtml(image)}" class="w-full h-full object-cover"></button>`).join('')}</div>`
      : '';

    const originalPrice = product.originalPrice
      ? `<span class="text-lg line-through text-gray-400">$${product.originalPrice.toFixed(2)}</span>`
      : '';

    const relatedHtml = related.length
      ? `<section class="mt-16"><h2 class="text-xl font-bold mb-6">Related Products</h2><div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">${related.map((item) => `<div class="rounded-xl bg-white shadow-sm overflow-hidden"><a href="/product/${encodeURIComponent(item.slug)}"><div class="aspect-square overflow-hidden bg-gray-100"><img src="${window.escapeHtml(item.image)}" alt="${window.escapeHtml(item.name)}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" loading="lazy"></div></a><div class="p-4"><p class="text-xs text-gray-500 mb-1">${window.escapeHtml(item.category)}</p><a href="/product/${encodeURIComponent(item.slug)}" class="font-semibold text-sm hover:text-accent transition-colors line-clamp-1">${window.escapeHtml(item.name)}</a><div class="flex items-center gap-1 mt-1.5">${detailStars(item.rating, 'w-3 h-3')}</div><p class="font-bold mt-2">$${item.price.toFixed(2)}</p></div></div>`).join('')}</div></section>`
      : '';

    const reviews = product.reviews || [];
    const reviewForm = window.MART_API.isAuthenticated()
      ? `<form id="review-form" class="p-5 rounded-xl bg-white shadow-sm space-y-4 mt-6"><h3 class="font-semibold text-lg">Write a Review</h3><div><label class="text-sm font-medium text-gray-700 mb-1 block">Rating</label><select name="rating" class="w-full px-4 py-2.5 rounded-lg bg-gray-100 border-none outline-none text-sm"><option value="5">5 Stars</option><option value="4">4 Stars</option><option value="3">3 Stars</option><option value="2">2 Stars</option><option value="1">1 Star</option></select></div><div><label class="text-sm font-medium text-gray-700 mb-1 block">Comment</label><textarea name="comment" rows="4" required class="w-full px-4 py-2.5 rounded-lg bg-gray-100 border-none outline-none text-sm resize-none"></textarea></div><button type="submit" class="px-5 py-3 rounded-xl bg-gray-900 text-white font-semibold hover:opacity-90 transition-opacity">Submit Review</button></form>`
      : `<div class="p-5 rounded-xl bg-white shadow-sm mt-6"><p class="text-gray-500 text-sm">Want to leave a review? <a href="${window.MART_CONFIG.routes.login}?redirect=${encodeURIComponent(window.location.pathname)}" class="text-accent font-medium">Log in first</a>.</p></div>`;

    const reviewsHtml = `<section class="mt-16"><h2 class="text-xl font-bold mb-6">Customer Reviews</h2>${reviews.length ? `<div class="space-y-4">${reviews.map((review) => `<article class="p-5 rounded-xl bg-white shadow-sm"><div class="flex items-center justify-between gap-4 mb-3"><div><p class="font-semibold">${window.escapeHtml(review.userName || 'Customer')}</p><div class="flex items-center gap-1 mt-1">${detailStars(review.rating, 'w-3 h-3')}</div></div><p class="text-xs text-gray-400">${new Date(review.createdAt).toLocaleDateString()}</p></div><p class="text-sm text-gray-500 leading-relaxed">${window.escapeHtml(review.comment)}</p></article>`).join('')}</div>` : '<div class="p-5 rounded-xl bg-white shadow-sm text-gray-500 text-sm">No reviews yet. Be the first to share your feedback.</div>'}${reviewForm}</section>`;

    main.innerHTML = `
      <a href="/shop" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-900 mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg> Back to Shop
      </a>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
        <div>
          <div class="aspect-square rounded-xl overflow-hidden bg-gray-100"><img id="main-image" src="${window.escapeHtml(product.images[0])}" alt="${window.escapeHtml(product.name)}" class="w-full h-full object-cover"></div>
          ${thumbs}
        </div>
        <div class="flex flex-col">
          <p class="text-sm text-gray-500 mb-2">${window.escapeHtml(product.category)}</p>
          <h1 class="text-2xl sm:text-3xl font-bold mb-3">${window.escapeHtml(product.name)}</h1>
          <div class="flex items-center gap-2 mb-4"><div class="flex gap-0.5">${detailStars(product.rating)}</div><span class="text-sm text-gray-400">(${product.reviewCount} reviews)</span></div>
          <div class="flex items-center gap-3 mb-6"><span class="text-3xl font-bold">$${product.price.toFixed(2)}</span>${originalPrice}</div>
          <p class="text-gray-500 leading-relaxed mb-6">${window.escapeHtml(product.description)}</p>
          <div class="flex flex-wrap gap-2 mb-8">${product.features.map((feature) => `<span class="text-xs font-medium px-3 py-1 rounded-full bg-gray-100 text-gray-700">${window.escapeHtml(feature)}</span>`).join('')}</div>
          <button onclick="addToCart(${product.id})" class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-gray-900 text-white font-semibold hover:opacity-90 transition-opacity">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            Add to Cart
          </button>
        </div>
      </div>
      ${reviewsHtml}
      ${relatedHtml}
    `;

    window.selectImage = function(index) {
      document.getElementById('main-image').src = product.images[index];
      document.querySelectorAll('#product-main button[onclick^="selectImage"]').forEach((button, buttonIndex) => {
        button.className = `w-20 h-20 rounded-lg overflow-hidden border-2 transition-colors ${buttonIndex === index ? 'border-accent' : 'border-gray-200'}`;
      });
    };

    const reviewFormElement = document.getElementById('review-form');
    if (reviewFormElement) {
      reviewFormElement.addEventListener('submit', async (event) => {
        event.preventDefault();

        const formData = new FormData(reviewFormElement);
        try {
          await window.MART_API.createReview(product.id, {
            rating: Number(formData.get('rating')),
            comment: formData.get('comment'),
          });
          window.showToast('Review submitted successfully.');
          await renderProductPage();
        } catch (error) {
          if (error.status === 401) {
            window.MART_API.redirectToLogin();
            return;
          }
          window.showToast(error.message || 'Unable to submit review.', true);
        }
      });
    }
  } catch (error) {
    main.innerHTML = `<div class="text-center py-16"><p class="text-gray-500">${window.escapeHtml(error.message || 'Product not found.')}</p><a href="/shop" class="text-accent mt-4 inline-block">&larr; Back to Shop</a></div>`;
  }
}

document.addEventListener('DOMContentLoaded', renderProductPage);
