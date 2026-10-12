<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteIdentity extends Model
{
    protected $fillable = [
        'name',
        'description',
        'welcome_title',
        'welcome_subtitle',
        'email',
        'phone',
        'header_contact_text',
        'whatsapp',
        'address',
        'google_maps_embed',
        'logo',
        'logo_secondary',
        'favicon',
        'footer_logo',
        'leaders',
        'facebook',
        'instagram',
        'twitter',
        'youtube',
        'tiktok',
        'footer_links_related',
        'footer_links_city',
        'footer_links_regency',
        'gpr_widget_status',
        'gpr_widget_rss_url',
        'survey_link_url',
        'seo',
        'social_links',
        'jam_pelayanan',
        'latitude',
        'longitude',
        'alert_active',
        'alert_text',
        'alert_level',
        'hero_image',
        'hero_image_kredit',
        'sejarah',
        'visi',
        'misi',
        'geografi',
        'batas_utara',
        'batas_timur',
        'batas_selatan',
        'batas_barat',
        'akses',
        'akses_catatan',
        'akses_diperbarui',
    ];

    protected $casts = [
        'leaders'               => 'array',
        'footer_links_related'  => 'array',
        'footer_links_city'     => 'array',
        'footer_links_regency'  => 'array',
        'gpr_widget_status'     => 'boolean',
        'seo'                   => 'array',
        'social_links'          => 'array',
        'alert_active'          => 'boolean',
        'akses'                 => 'array',
        'akses_diperbarui'      => 'date',
    ];
}
