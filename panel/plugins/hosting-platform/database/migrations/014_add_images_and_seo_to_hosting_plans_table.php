<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hosting_plans', function (Blueprint $table) {
            $table->string('image')->nullable()->after('tagline');
            $table->string('image_url')->nullable()->after('image');
            $table->string('badge')->nullable()->after('image_url');
            $table->string('icon')->nullable()->after('badge');
            $table->string('banner_color')->nullable()->after('icon');
            $table->json('features')->nullable()->after('banner_color');

            // Search Engine Optimization (SEO) & OpenGraph
            $table->string('meta_title')->nullable()->after('sort_order');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->text('meta_keywords')->nullable()->after('meta_description');
            $table->string('og_image')->nullable()->after('meta_keywords');
            $table->boolean('is_search_indexed')->default(true)->after('og_image');
        });

        Schema::table('hosting_categories', function (Blueprint $table) {
            $table->string('banner_image')->nullable()->after('icon');
            $table->string('meta_title')->nullable()->after('sort_order');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->text('meta_keywords')->nullable()->after('meta_description');
            $table->string('og_image')->nullable()->after('meta_keywords');
        });

        Schema::table('hosting_products', function (Blueprint $table) {
            $table->string('banner_image')->nullable()->after('icon');
            $table->string('meta_title')->nullable()->after('sort_order');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->text('meta_keywords')->nullable()->after('meta_description');
            $table->string('og_image')->nullable()->after('meta_keywords');
        });
    }

    public function down(): void
    {
        Schema::table('hosting_plans', function (Blueprint $table) {
            $table->dropColumn([
                'image', 'image_url', 'badge', 'icon', 'banner_color', 'features',
                'meta_title', 'meta_description', 'meta_keywords', 'og_image', 'is_search_indexed'
            ]);
        });

        Schema::table('hosting_categories', function (Blueprint $table) {
            $table->dropColumn(['banner_image', 'meta_title', 'meta_description', 'meta_keywords', 'og_image']);
        });

        Schema::table('hosting_products', function (Blueprint $table) {
            $table->dropColumn(['banner_image', 'meta_title', 'meta_description', 'meta_keywords', 'og_image']);
        });
    }
};
