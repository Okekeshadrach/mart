<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'about_features' => 'array',
            'about_stats' => 'array',
        ];
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            static::defaultAttributes()
        );
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaultAttributes(): array
    {
        return [
            'site_name' => 'MART',
            'site_tagline' => 'Your one-stop shop for quality products across every category.',
            'meta_description' => 'Shop thousands of products across electronics, fashion, home, sports and beauty at great prices. Free shipping on all orders.',
            'support_email' => 'support@mart.store',
            'support_phone' => '1-800-MART-123',
            'support_address' => "123 Commerce St, Suite 100\nSan Francisco, CA 94102",
            'contact_title' => 'Get in Touch',
            'contact_description' => "Have a question about an order, product, or just want to say hello? We'd love to hear from you.",
            'contact_form_success_message' => "Message sent! We'll get back to you soon.",
            'about_title' => 'About MART',
            'about_description' => 'We believe shopping should be simple, enjoyable, and accessible. MART brings together thousands of quality products across every category from electronics and fashion to home essentials and beauty all in one place.',
            'shipping_summary_label' => 'Free',
            'about_features' => [
                [
                    'title' => 'Curated Selection',
                    'description' => 'Every product is hand-picked for quality, value, and customer satisfaction.',
                ],
                [
                    'title' => 'Fast Shipping',
                    'description' => 'Free shipping on every order with express delivery options available.',
                ],
                [
                    'title' => 'Secure Payments',
                    'description' => 'Your transactions are protected with industry-leading encryption.',
                ],
            ],
            'about_stats' => [
                ['value' => '10K+', 'label' => 'Products'],
                ['value' => '50K+', 'label' => 'Happy Customers'],
                ['value' => '99%', 'label' => 'Satisfaction'],
                ['value' => '24/7', 'label' => 'Support'],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toPublicArray(): array
    {
        return [
            'siteName' => $this->site_name,
            'siteTagline' => $this->site_tagline,
            'metaDescription' => $this->meta_description,
            'supportEmail' => $this->support_email,
            'supportPhone' => $this->support_phone,
            'supportAddress' => $this->support_address,
            'contactTitle' => $this->contact_title,
            'contactDescription' => $this->contact_description,
            'contactFormSuccessMessage' => $this->contact_form_success_message,
            'aboutTitle' => $this->about_title,
            'aboutDescription' => $this->about_description,
            'shippingSummaryLabel' => $this->shipping_summary_label,
            'aboutFeatures' => $this->about_features ?? [],
            'aboutStats' => $this->about_stats ?? [],
        ];
    }
}
