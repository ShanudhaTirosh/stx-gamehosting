<?php

namespace BrandedHosting\Platform\Models;

use BrandedHosting\Platform\Enums\BillingPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class HostingPlan extends Model
{
    protected $table = 'hosting_plans';

    protected $fillable = [
        'product_id',
        'name',
        'slug',
        'tagline',
        'image',
        'image_url',
        'badge',
        'icon',
        'banner_color',
        'features',
        'price_lkr',
        'setup_fee_lkr',
        'billing_period',
        'memory_mb',
        'cpu_percent',
        'disk_mb',
        'swap_mb',
        'io_weight',
        'database_limit',
        'allocation_limit',
        'backup_limit',
        'is_featured',
        'is_available',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'is_search_indexed',
    ];

    protected $casts = [
        'price_lkr' => 'decimal:2',
        'setup_fee_lkr' => 'decimal:2',
        'billing_period' => BillingPeriod::class,
        'features' => 'array',
        'memory_mb' => 'integer',
        'cpu_percent' => 'integer',
        'disk_mb' => 'integer',
        'swap_mb' => 'integer',
        'io_weight' => 'integer',
        'database_limit' => 'integer',
        'allocation_limit' => 'integer',
        'backup_limit' => 'integer',
        'is_featured' => 'boolean',
        'is_available' => 'boolean',
        'sort_order' => 'integer',
        'is_search_indexed' => 'boolean',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (HostingPlan $plan) {
            if (empty($plan->slug)) {
                $plan->slug = Str::slug($plan->name . '-' . Str::random(4));
            }
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(HostingProduct::class, 'product_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(HostingOrder::class, 'plan_id');
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rs. ' . number_format((float) $this->price_lkr, 2) . ' LKR';
    }

    public function getMemoryGbAttribute(): float
    {
        return round($this->memory_mb / 1024, 1);
    }

    public function getDiskGbAttribute(): float
    {
        return round($this->disk_mb / 1024, 1);
    }

    public function getDisplayImageAttribute(): string
    {
        if (!empty($this->image_url)) {
            return $this->image_url;
        }

        if (!empty($this->image)) {
            return '/storage/' . ltrim($this->image, '/');
        }

        // Category/product artwork fallback
        $catSlug = $this->product?->category?->slug;
        if ($catSlug) {
            if (str_contains($catSlug, 'minecraft')) {
                return '/images/plans/minecraft.svg';
            }
            if (str_contains($catSlug, 'hytale')) {
                return '/images/plans/hytale.svg';
            }
            if (str_contains($catSlug, 'fivem') || str_contains($catSlug, 'gta')) {
                return '/images/plans/fivem.svg';
            }
            if (str_contains($catSlug, 'mta')) {
                return '/images/plans/mtasa.svg';
            }
            if (str_contains($catSlug, 'bot')) {
                return '/images/plans/bot.svg';
            }
            if (str_contains($catSlug, 'web')) {
                return '/images/plans/web.svg';
            }
        }

        return '/images/plans/default-server.svg';
    }

    public function getDisplayBadgeAttribute(): string
    {
        if (!empty($this->badge)) {
            return $this->badge;
        }

        if ($this->is_featured) {
            return 'Most Popular';
        }

        return 'Enterprise Grade';
    }

    public function getDisplayFeaturesAttribute(): array
    {
        if (is_array($this->features) && count($this->features) > 0) {
            return $this->features;
        }

        // Automatic rich feature breakdown
        $list = [
            "Dedicated RAM: {$this->memory_gb} GB High-Speed DDR5",
            "NVMe Storage: {$this->disk_gb} GB Gen4 Enterprise SSD",
            "Compute: {$this->cpu_percent}% Dedicated vCPU Core",
            "Instant Automated Deployment upon payment",
            "Automated Daily Disaster Recovery Backups",
        ];

        if ($this->database_limit > 0) {
            $list[] = "{$this->database_limit} Dedicated MariaDB / MySQL Databases";
        }

        $list[] = "Anti-DDoS Enterprise Shield (100+ Gbps)";
        $list[] = "Full Web Console & SFTP File Access";

        return $list;
    }

    public function getSeoMetaTitle(): string
    {
        if (!empty($this->meta_title)) {
            return $this->meta_title;
        }

        $productName = $this->product?->name ?: 'Game Server';
        return "{$this->name} - {$productName} Hosting | Low Latency Sri Lanka";
    }

    public function getSeoMetaDescription(): string
    {
        if (!empty($this->meta_description)) {
            return $this->meta_description;
        }

        $productName = $this->product?->name ?: 'Game Server';
        return "Deploy {$this->name} for {$productName} with {$this->memory_gb}GB RAM, {$this->disk_gb}GB NVMe SSD, and {$this->cpu_percent}% CPU. Instant auto-provisioning with Sri Lanka local payment rails.";
    }

    public function getSeoKeywords(): string
    {
        if (!empty($this->meta_keywords)) {
            return $this->meta_keywords;
        }

        $productName = Str::lower($this->product?->name ?: 'server');
        return "{$productName} hosting, {$this->slug}, cheap {$productName} server, sri lanka {$productName} hosting, game server hosting, stx enterprises";
    }

    public function getOgImage(): string
    {
        return $this->og_image ?: $this->display_image;
    }
}
