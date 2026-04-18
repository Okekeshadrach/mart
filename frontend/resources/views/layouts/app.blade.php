<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'MART — Your One-Stop Multi-Product Store')</title>
  <meta id="site-meta-description" data-template="@yield('meta_description_template', '{metaDescription}')" name="description" content="@yield('meta_description', 'Shop thousands of products across electronics, fashion, home, sports and beauty at great prices. Free shipping on all orders.')">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['Space Grotesk', 'sans-serif'] },
          colors: {
            accent: 'hsl(20, 100%, 54%)',
            'accent-fg': '#fff',
            surface: 'hsl(0,0%,96%)',
            muted: 'hsl(0,0%,45%)',
            border: 'hsl(0,0%,90%)',
          }
        }
      }
    }
  </script>
  <style>
    body { font-family: 'Space Grotesk', sans-serif; }
    .product-card:hover .product-img { transform: scale(1.05); }
    .product-card:hover { box-shadow: 0 8px 30px -12px rgba(0,0,0,0.15); }
  </style>
  <script>
    window.MART_CONFIG = {
      apiBaseUrl: @json(rtrim(config('services.mart_api.base_url'), '/')),
      routes: {
        shop: @json(route('shop')),
        cart: @json(route('cart')),
        checkout: @json(route('checkout')),
        orders: @json(route('orders')),
        login: @json(route('login')),
        register: @json(route('register')),
        profile: @json(route('profile')),
      }
    };
    window.MART_PAGE = window.MART_PAGE || {};
  </script>
  @stack('styles')
</head>
<body data-page-title-template="@yield('page_title_template', '{siteName} — {siteTagline}')" class="bg-[#fafafa] text-gray-900 antialiased min-h-screen flex flex-col">

  @include('partials.header')

  @yield('content')

  @include('partials.footer')

  <script src="{{ asset('js/api.js') }}"></script>
  <script src="{{ asset('js/guest-cart.js') }}"></script>
  <script src="{{ asset('js/site-settings.js') }}"></script>
  <script src="{{ asset('js/auth.js') }}"></script>
  <script src="{{ asset('js/cart.js') }}"></script>
  @stack('scripts')
</body>
</html>
