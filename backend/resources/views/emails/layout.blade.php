<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $subject ?? 'MART' }}</title>
<style>
  body { margin: 0; padding: 0; background-color: #fafafa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #1a1a1a; }
  .wrapper { width: 100%; background-color: #fafafa; padding: 32px 0; }
  .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
  .header { background-color: #1a1a1a; padding: 24px 32px; text-align: center; }
  .header a { color: #ffffff; text-decoration: none; font-size: 22px; font-weight: 700; letter-spacing: -0.5px; }
  .header .dot { color: hsl(20, 100%, 54%); }
  .body { padding: 32px; }
  .body h1 { font-size: 22px; font-weight: 700; margin: 0 0 8px; }
  .body p { font-size: 15px; line-height: 1.6; color: #555; margin: 0 0 16px; }
  .btn { display: inline-block; padding: 12px 28px; background-color: #1a1a1a; color: #ffffff !important; text-decoration: none; border-radius: 10px; font-size: 14px; font-weight: 600; }
  .btn:hover { opacity: 0.9; }
  .divider { border: none; border-top: 1px solid #eee; margin: 24px 0; }
  .table { width: 100%; border-collapse: collapse; margin: 16px 0; }
  .table th { text-align: left; padding: 10px 12px; background-color: #f5f5f5; font-size: 13px; font-weight: 600; color: #555; border-bottom: 1px solid #eee; }
  .table td { padding: 10px 12px; font-size: 14px; border-bottom: 1px solid #f5f5f5; }
  .table .total-row td { font-weight: 700; font-size: 16px; border-top: 2px solid #1a1a1a; border-bottom: none; padding-top: 14px; }
  .detail-grid { margin: 16px 0; }
  .detail-grid .label { font-size: 12px; font-weight: 600; color: #999; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 4px; }
  .detail-grid .value { font-size: 14px; color: #333; margin: 0 0 16px; }
  .footer { padding: 24px 32px; text-align: center; background-color: #fafafa; border-top: 1px solid #eee; }
  .footer p { font-size: 12px; color: #999; margin: 0; line-height: 1.6; }
  .accent { color: hsl(20, 100%, 54%); }
</style>
</head>
<body>
<div class="wrapper">
  <div class="container">
    <div class="header">
      <a href="{{ config('app.frontend_url') }}">MART<span class="dot">.</span></a>
    </div>
    <div class="body">
      @yield('content')
    </div>
    <div class="footer">
      <p>&copy; {{ date('Y') }} MART. All rights reserved.</p>
      <p style="margin-top: 4px;">{{ config('mail.from.address') }}</p>
    </div>
  </div>
</div>
</body>
</html>
