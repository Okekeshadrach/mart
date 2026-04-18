<footer class="border-t border-gray-200 bg-white mt-auto">
  <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-1 md:grid-cols-3 gap-8">
    <div>
      <a href="{{ route('shop') }}" class="text-lg font-bold"><span data-site-setting="siteName">MART</span><span class="text-accent">.</span></a>
      <p data-site-setting="siteTagline" class="text-sm text-gray-500 mt-2">Your one-stop shop for quality products across every category.</p>
    </div>
    <div>
      <h4 class="font-semibold text-sm mb-3">Quick Links</h4>
      <div class="space-y-2 text-sm text-gray-500">
        <a href="{{ route('shop') }}" class="block hover:text-gray-900">Shop</a>
        <a href="{{ route('about') }}" class="block hover:text-gray-900">About</a>
        <a href="{{ route('contact') }}" class="block hover:text-gray-900">Contact</a>
      </div>
    </div>
    <div>
      <h4 class="font-semibold text-sm mb-3">Support</h4>
      <div class="space-y-2 text-sm text-gray-500">
        <a data-site-setting="supportEmail" href="mailto:support@mart.store" class="block hover:text-gray-900">support@mart.store</a>
        <a data-site-setting="supportPhone" href="tel:1-800-MART-123" class="block hover:text-gray-900">1-800-MART-123</a>
      </div>
    </div>
  </div>
  <div id="footer-copyright" data-template="© {year} {siteName}. All rights reserved." class="border-t border-gray-200 py-4 text-center text-xs text-gray-400">&copy; {{ date('Y') }} MART. All rights reserved.</div>
</footer>
