@extends('emails.layout', ['subject' => 'Welcome to MART'])

@section('content')
<h1>Welcome, {{ $user->name }}!</h1>
<p>Thanks for creating an account with MART. You now have access to thousands of quality products across every category.</p>
<p>Start exploring our collection and find something you love.</p>
<p style="text-align: center; margin: 28px 0;">
  <a href="{{ config('app.frontend_url') }}/shop" class="btn">Start Shopping</a>
</p>
<hr class="divider">
<p style="font-size: 13px; color: #999;">If you didn't create this account, please ignore this email.</p>
@endsection
