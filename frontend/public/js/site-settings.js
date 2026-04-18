function compileTemplate(template, settings) {
  return String(template || '')
    .replaceAll('{siteName}', settings.siteName || '')
    .replaceAll('{siteTagline}', settings.siteTagline || '')
    .replaceAll('{metaDescription}', settings.metaDescription || '')
    .replaceAll('{year}', String(new Date().getFullYear()));
}

function renderAddressHtml(value) {
  return window.escapeHtml(value || '').replace(/\n/g, '<br>');
}

function updateText(selector, value) {
  document.querySelectorAll(selector).forEach((element) => {
    element.textContent = value ?? '';
  });
}

function updateLink(selector, value, prefix) {
  document.querySelectorAll(selector).forEach((element) => {
    element.textContent = value ?? '';

    if (value) {
      element.setAttribute('href', `${prefix}${value}`);
    }
  });
}

function renderAboutFeatures(features) {
  const container = document.getElementById('about-features');

  if (!container || !Array.isArray(features) || !features.length) {
    return;
  }

  const icons = [
    '<svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>',
    '<svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>',
    '<svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
  ];

  container.innerHTML = features.slice(0, 3).map((feature, index) => `<div class="p-8 rounded-xl bg-white shadow-sm text-center"><div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">${icons[index] || icons[0]}</div><h3 class="font-bold mb-2">${window.escapeHtml(feature.title)}</h3><p class="text-sm text-gray-500">${window.escapeHtml(feature.description)}</p></div>`).join('');
}

function renderAboutStats(stats) {
  const container = document.getElementById('about-stats');

  if (!container || !Array.isArray(stats) || !stats.length) {
    return;
  }

  container.innerHTML = stats.slice(0, 4).map((stat) => `<div><p class="text-3xl font-bold text-accent">${window.escapeHtml(stat.value)}</p><p class="text-sm text-gray-400 mt-1">${window.escapeHtml(stat.label)}</p></div>`).join('');
}

function applySiteSettings(settings) {
  window.MART_SITE_SETTINGS = settings;

  updateText('[data-site-setting="siteName"]', settings.siteName);
  updateText('[data-site-setting="siteTagline"]', settings.siteTagline);
  updateText('[data-site-setting="aboutTitle"]', settings.aboutTitle);
  updateText('[data-site-setting="aboutDescription"]', settings.aboutDescription);
  updateText('[data-site-setting="contactTitle"]', settings.contactTitle);
  updateText('[data-site-setting="contactDescription"]', settings.contactDescription);

  updateLink('[data-site-setting="supportEmail"]', settings.supportEmail, 'mailto:');
  updateLink('[data-site-setting="supportPhone"]', settings.supportPhone, 'tel:');

  const addressElement = document.querySelector('[data-site-setting="supportAddress"]');
  if (addressElement) {
    addressElement.innerHTML = renderAddressHtml(settings.supportAddress);
  }

  const footerCopyright = document.getElementById('footer-copyright');
  if (footerCopyright) {
    footerCopyright.textContent = compileTemplate(footerCopyright.dataset.template, settings);
  }

  const metaDescription = document.getElementById('site-meta-description');
  if (metaDescription) {
    metaDescription.setAttribute('content', compileTemplate(metaDescription.dataset.template, settings));
  }

  const pageTitleTemplate = document.body.dataset.pageTitleTemplate;
  if (pageTitleTemplate) {
    document.title = compileTemplate(pageTitleTemplate, settings);
  }

  renderAboutFeatures(settings.aboutFeatures || []);
  renderAboutStats(settings.aboutStats || []);

  document.dispatchEvent(new CustomEvent('mart:site-settings-loaded', {
    detail: settings,
  }));
}

async function loadSiteSettings(force = false) {
  try {
    const settings = await window.MART_API.fetchSiteSettings(force);
    applySiteSettings(settings);
    return settings;
  } catch (error) {
    return window.MART_SITE_SETTINGS || null;
  }
}

document.addEventListener('DOMContentLoaded', () => {
  loadSiteSettings();
});

window.loadSiteSettings = loadSiteSettings;
