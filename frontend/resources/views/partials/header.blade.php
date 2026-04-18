<header class="sticky top-0 z-50 bg-[#fafafa]/85 backdrop-blur-md border-b border-gray-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
    <a href="{{ route('shop') }}" class="text-xl font-bold tracking-tight"><span data-site-setting="siteName">MART</span><span class="text-accent">.</span></a>
    <nav class="hidden md:flex gap-6 text-sm font-medium text-gray-500">
      <a href="{{ route('shop') }}" class="hover:text-gray-900 transition-colors {{ request()->routeIs('home', 'shop') ? 'text-gray-900' : '' }}">Shop</a>
      <a href="{{ route('orders') }}" id="desktop-orders-link" class="hidden hover:text-gray-900 transition-colors {{ request()->routeIs('orders') ? 'text-gray-900' : '' }}">Orders</a>
      <a href="{{ route('about') }}" class="hover:text-gray-900 transition-colors {{ request()->routeIs('about') ? 'text-gray-900' : '' }}">About</a>
      <a href="{{ route('contact') }}" class="hover:text-gray-900 transition-colors {{ request()->routeIs('contact') ? 'text-gray-900' : '' }}">Contact</a>
    </nav>
    <div class="flex items-center gap-3">
      @hasSection('search')
        @yield('search')
      @endif
      <div id="auth-guest" class="hidden md:flex items-center gap-2">
        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">Login</a>
        <a href="{{ route('register') }}" class="px-3 py-2 rounded-full bg-gray-900 text-white text-sm font-medium hover:opacity-90 transition-opacity">Register</a>
      </div>
      <div id="auth-user" class="hidden items-center gap-3">
        <a href="{{ route('profile') }}" id="auth-user-name" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors"></a>
        <button id="logout-button" type="button" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">Logout</button>
      </div>
      <a href="{{ route('cart') }}" class="relative p-2 rounded-full bg-gray-900 text-white">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        <span id="cart-count" class="hidden absolute -top-1 -right-1 text-xs w-4 h-4 rounded-full flex items-center justify-center bg-accent text-white font-bold">0</span>
      </a>
      <button class="md:hidden text-gray-500" onclick="document.getElementById('mobile-nav').classList.toggle('hidden')">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
    </div>
  </div>
  <div id="mobile-nav" class="hidden md:hidden border-t border-gray-200 bg-[#fafafa] px-6 py-4 space-y-3">
    <a href="{{ route('shop') }}" class="block text-sm font-medium text-gray-500 hover:text-gray-900">Shop</a>
    <a href="{{ route('orders') }}" id="mobile-orders-link" class="hidden block text-sm font-medium text-gray-500 hover:text-gray-900">Orders</a>
    <a href="{{ route('about') }}" class="block text-sm font-medium text-gray-500 hover:text-gray-900">About</a>
    <a href="{{ route('contact') }}" class="block text-sm font-medium text-gray-500 hover:text-gray-900">Contact</a>
    <div id="mobile-auth-guest" class="hidden space-y-3 pt-2 border-t border-gray-200">
      <a href="{{ route('login') }}" class="block text-sm font-medium text-gray-500 hover:text-gray-900">Login</a>
      <a href="{{ route('register') }}" class="block text-sm font-medium text-gray-500 hover:text-gray-900">Register</a>
    </div>
    <div id="mobile-auth-user" class="hidden space-y-3 pt-2 border-t border-gray-200">
      <a href="{{ route('profile') }}" id="mobile-auth-user-name" class="block text-sm font-medium text-gray-500 hover:text-gray-900"></a>
      <button id="mobile-logout-button" type="button" class="block text-sm font-medium text-gray-500 hover:text-gray-900">Logout</button>
    </div>
  </div>
</header>
