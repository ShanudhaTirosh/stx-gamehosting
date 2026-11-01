<?php

namespace BrandedHosting\Platform\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class HostingSeoSetting extends Model
{
    protected $table = 'hosting_seo_settings';

    protected $fillable = [
        'site_name',
        'site_tagline',
        'title_separator',
        'meta_title_template',
        'meta_description',
        'meta_keywords',
        'og_image',
        'twitter_handle',
        'robots_policy',
        'google_site_verification',
        'bing_site_verification',
        'analytics_tag_id',
        'custom_head_scripts',
        'custom_body_scripts',
        'company_legal_name',
        'contact_email',
        'contact_phone',
        'support_discord_url',
        'support_whatsapp_url',
        'status_page_url',
        'sitemap_enabled',
        'schema_org_enabled',
        'schema_price_range',
    ];

    protected $casts = [
        'sitemap_enabled' => 'boolean',
        'schema_org_enabled' => 'boolean',
    ];

    public static function current(): self
    {
        return Cache::remember('hosting_seo_settings_current', 3600, function () {
            $setting = static::first();
            if (!$setting) {
                $setting = static::create([
                    'site_name' => 'STX Enterprises',
                    'site_tagline' => 'Enterprise Cloud & Game Server Infrastructure',
                    'title_separator' => '—',
                    'meta_title_template' => '{title} {separator} {site_name}',
                    'meta_description' => 'Deploy ultra-fast game servers, low-latency containerized instances, and enterprise cloud solutions in Sri Lanka & Singapore. Instant auto-provisioning with 99.98% SLA.',
                    'meta_keywords' => 'game server hosting, minecraft hosting sri lanka, fivem hosting colombo, cloud vps sri lanka, low latency game hosting, stx enterprises',
                    'og_image' => '/images/plans/default-server.svg',
                    'twitter_handle' => '@STXEnterprises',
                    'robots_policy' => 'index, follow',
                    'company_legal_name' => 'STX Enterprises Lanka (Pvt) Ltd',
                    'contact_email' => 'support@shanutechx.com',
                    'contact_phone' => '+94 11 234 5678',
                    'support_discord_url' => 'https://discord.gg/stxenterprises',
                    'support_whatsapp_url' => 'https://chat.whatsapp.com/stxenterprises',
                    'status_page_url' => '/status',
                    'sitemap_enabled' => true,
                    'schema_org_enabled' => true,
                    'schema_price_range' => 'LKR 1,500 - 15,000',
                ]);
            }
            return $setting;
        });
    }

    public static function clearCache(): void
    {
        Cache::forget('hosting_seo_settings_current');
    }

    public function renderTitle(?string $title = null): string
    {
        if (empty($title)) {
            return "{$this->site_name} {$this->title_separator} {$this->site_tagline}";
        }

        $template = $this->meta_title_template ?: '{title} {separator} {site_name}';

        return str_replace(
            ['{title}', '{separator}', '{site_name}', '{tagline}'],
            [$title, $this->title_separator ?: '—', $this->site_name ?: 'STX Enterprises', $this->site_tagline ?: ''],
            $template
        );
    }
}
