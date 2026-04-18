@extends('emails.layout', ['subject' => 'Reset Your Password'])

@section('content')
<h1>Password Reset</h1>
<p>Hi {{ $user->name }}, we received a request to reset your password. Click the button below to choose a new one.</p>
<p style="text-align: center; margin: 28px 0;">
  <a href="{{ $resetUrl }}" class="btn">Reset Password</a>
</p>
<p style="font-size: 13px; color: #999;">This link will expire in 60 minutes. If you didn't request a password reset, you can safely ignore this email.</p>
<hr class="divider">
<p style="font-size: 13px; color: #999;">If the button doesn't work, copy and paste this URL into your browser:</p>
<p style="font-size: 12px; word-break: break-all; color: #999;">{{ $resetUrl }}</p>
@endsection
