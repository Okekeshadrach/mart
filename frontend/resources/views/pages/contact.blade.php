@extends('layouts.app')

@section('title', 'Contact - MART')
@section('page_title_template', 'Contact — {siteName}')

@section('content')
<main class="flex-grow max-w-7xl mx-auto px-6 py-16">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
    <div>
      <h1 data-site-setting="contactTitle" class="text-3xl font-bold mb-4">Get in Touch</h1>
      <p data-site-setting="contactDescription" class="text-gray-500 mb-8 leading-relaxed">Have a question about an order, product, or just want to say hello? We'd love to hear from you.</p>
      <div class="space-y-4">
        <div class="flex items-start gap-3">
          <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          </div>
          <div><p class="font-medium text-sm">Email</p><a data-site-setting="supportEmail" href="mailto:support@mart.store" class="text-sm text-gray-500 hover:text-gray-900">support@mart.store</a></div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          </div>
          <div><p class="font-medium text-sm">Phone</p><a data-site-setting="supportPhone" href="tel:1-800-MART-123" class="text-sm text-gray-500 hover:text-gray-900">1-800-MART-123</a></div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          </div>
          <div><p class="font-medium text-sm">Address</p><p data-site-setting="supportAddress" class="text-sm text-gray-500">123 Commerce St, Suite 100<br>San Francisco, CA 94102</p></div>
        </div>
      </div>
    </div>
    <div>
      <form id="contact-form" onsubmit="handleContact(event)" class="p-6 rounded-xl bg-white shadow-sm space-y-4">
        <div><label class="text-sm font-medium text-gray-700 mb-1 block">Name</label><input name="name" required class="w-full px-4 py-2.5 rounded-lg bg-gray-100 border-none outline-none text-sm focus:ring-2 focus:ring-accent"></div>
        <div><label class="text-sm font-medium text-gray-700 mb-1 block">Email</label><input name="email" type="email" required class="w-full px-4 py-2.5 rounded-lg bg-gray-100 border-none outline-none text-sm focus:ring-2 focus:ring-accent"></div>
        <div><label class="text-sm font-medium text-gray-700 mb-1 block">Subject</label><input name="subject" required class="w-full px-4 py-2.5 rounded-lg bg-gray-100 border-none outline-none text-sm focus:ring-2 focus:ring-accent"></div>
        <div><label class="text-sm font-medium text-gray-700 mb-1 block">Message</label><textarea name="message" rows="4" required class="w-full px-4 py-2.5 rounded-lg bg-gray-100 border-none outline-none text-sm resize-none focus:ring-2 focus:ring-accent"></textarea></div>
        <button type="submit" id="contact-btn" class="w-full py-3 rounded-xl bg-gray-900 text-white font-semibold hover:opacity-90 transition-opacity">Send Message</button>
      </form>
    </div>
  </div>
</main>
@endsection

@push('scripts')
<script>
async function handleContact(e) {
  e.preventDefault();
  const form = document.getElementById('contact-form');
  const btn = document.getElementById('contact-btn');
  const data = Object.fromEntries(new FormData(form));

  btn.disabled = true;
  btn.textContent = 'Sending...';

  try {
    await window.MART_API.request('/contact', { method: 'POST', body: data });
    window.showToast(window.MART_SITE_SETTINGS?.contactFormSuccessMessage || "Message sent! We'll get back to you soon.");
    form.reset();
  } catch (error) {
    window.showToast(error.message || 'Unable to send message.', true);
  } finally {
    btn.disabled = false;
    btn.textContent = 'Send Message';
  }
}
</script>
@endpush
