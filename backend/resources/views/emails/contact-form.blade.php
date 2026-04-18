@extends('emails.layout', ['subject' => 'New Contact Message: ' . $data['subject']])

@section('content')
<h1>New Contact Message</h1>
<p>You received a new message from the contact form on MART.</p>

<div class="detail-grid">
  <p class="label">From</p>
  <p class="value">{{ $data['name'] }} ({{ $data['email'] }})</p>
  <p class="label">Subject</p>
  <p class="value">{{ $data['subject'] }}</p>
  <p class="label">Message</p>
  <p class="value" style="white-space: pre-line;">{{ $data['message'] }}</p>
</div>

<hr class="divider">
<p style="font-size: 13px; color: #999;">Reply directly to {{ $data['email'] }} to respond to this message.</p>
@endsection
