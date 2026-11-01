<?php

namespace BrandedHosting\Platform\Models;

use App\Models\Egg;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class HostingProduct extends Model
{
    protected $table = 'hosting_products';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'icon',
        'banner_image',
        'egg_id',
        'docker_image',
        'startup_command',
        'default_environment',
        'deployment_tags',
        'ports',
        'is_active',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
    ];

    protected $casts = [
        'default_environment' => 'array',
        'deployment_tags' => 'array',
        'ports' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (HostingProduct $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(HostingCategory::class, 'category_id');
    }

    public function egg(): BelongsTo
    {
        return $this->belongsTo(Egg::class, 'egg_id');
    }

    public function plans(): HasMany
    {
        return $this->hasMany(HostingPlan::class, 'product_id')->orderBy('sort_order');
    }

    public function getSeoMetaTitle(): string
    {
        return $this->meta_title ?: "{$this->name} Hosting Plans | STX Enterprises";
    }

    public function getSeoMetaDescription(): string
    {
        return $this->meta_description ?: ($this->description ?: "Deploy high-performance {$this->name} hosting with automated backups and low-latency Sri Lankan infrastructure.");
    }
}
