<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hosting_seo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('STX Enterprises');
            $table->string('site_tagline')->default('Enterprise Cloud & Game Server Infrastructure');
            $table->string('title_separator')->default('—');
            $table->string('meta_title_template')->default('{title} {separator} {site_name}');
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('og_image')->nullable();
            $table->string('twitter_handle')->nullable()->default('@STXEnterprises');
            $table->string('robots_policy')->default('index, follow');

            // Webmaster & Analytics
            $table->string('google_site_verification')->nullable();
            $table->string('bing_site_verification')->nullable();
            $table->string('analytics_tag_id')->nullable();
            $table->text('custom_head_scripts')->nullable();
            $table->text('custom_body_scripts')->nullable();

            // Contact & Organization
            $table->string('company_legal_name')->default('STX Enterprises Lanka (Pvt) Ltd');
            $table->string('contact_email')->default('support@shanutechx.com');
            $table->string('contact_phone')->default('+94 11 234 5678');
            $table->string('support_discord_url')->nullable()->default('https://discord.gg/stxenterprises');
            $table->string('support_whatsapp_url')->nullable();
            $table->string('status_page_url')->nullable()->default('/status');

            // XML Sitemap & Schema.org Structured Data
            $table->boolean('sitemap_enabled')->default(true);
            $table->boolean('schema_org_enabled')->default(true);
            $table->string('schema_price_range')->default('LKR 1,500 - 15,000');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hosting_seo_settings');
    }
};
