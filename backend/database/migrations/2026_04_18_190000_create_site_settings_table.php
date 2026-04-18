<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name');
            $table->string('site_tagline');
            $table->text('meta_description');
            $table->string('support_email');
            $table->string('support_phone');
            $table->text('support_address');
            $table->string('contact_title');
            $table->text('contact_description');
            $table->string('contact_form_success_message');
            $table->string('about_title');
            $table->text('about_description');
            $table->string('shipping_summary_label')->default('Free');
            $table->json('about_features')->nullable();
            $table->json('about_stats')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
