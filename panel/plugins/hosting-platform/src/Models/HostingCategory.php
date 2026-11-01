<?php

namespace BrandedHosting\Platform\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class HostingCategory extends Model
{
    protected $table = 'hosting_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'banner_image',
        'badge_text',
        'sort_order',
        'is_visible',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'sort_order' => 'integer',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (HostingCategory $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(HostingProduct::class, 'category_id')->orderBy('sort_order');
    }

    public function serverConfig(): HasOne
    {
        return $this->hasOne(HostingCategoryConfig::class, 'category_id');
    }

    public function getDisplayBannerAttribute(): string
    {
        if (!empty($this->banner_image)) {
            return $this->banner_image;
        }

        if (str_contains($this->slug, 'minecraft')) {
            return '/images/plans/minecraft.svg';
        }
        if (str_contains($this->slug, 'hytale')) {
            return '/images/plans/hytale.svg';
        }
        if (str_contains($this->slug, 'fivem') || str_contains($this->slug, 'gta')) {
            return '/images/plans/fivem.svg';
        }
        if (str_contains($this->slug, 'mta')) {
            return '/images/plans/mtasa.svg';
        }
        if (str_contains($this->slug, 'bot')) {
            return '/images/plans/bot.svg';
        }
        if (str_contains($this->slug, 'web')) {
            return '/images/plans/web.svg';
        }

        return '/images/plans/default-server.svg';
    }

    public function getSeoMetaTitle(): string
    {
        return $this->meta_title ?: "{$this->name} Hosting Plans & Servers | Low Latency Sri Lanka";
    }

    public function getSeoMetaDescription(): string
    {
        return $this->meta_description ?: ($this->description ?: "High-performance {$this->name} hosting with automated backups, DDoS shield, and local Sri Lankan payment rails.");
    }

    public function getSeoKeywords(): string
    {
        return $this->meta_keywords ?: "{$this->slug} hosting, {$this->name} sri lanka, low ping {$this->name}, dedicated server {$this->name}, game server colombo";
    }

    public function getOgImage(): string
    {
        return $this->og_image ?: $this->display_banner;
    }
}
