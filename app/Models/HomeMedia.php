<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HomeMedia extends Model
{
    protected $table = 'home_media';

    protected $fillable = [
        'title',
        'description',
        'price',
        'service_type',
        'service_id',
        'media_type',
        'media_path',
        'poster_image',
        'section',
        'display_order',
        'is_active',
    ];

    // Media types
    public const TYPE_IMAGE = 'image';
    public const TYPE_VIDEO = 'video';

    // Sections
    public const SECTION_HERO = 'hero';
    public const SECTION_GALLERY = 'gallery';
    public const SECTION_TESTIMONIALS = 'testimonials';
    public const SECTION_SERVICES = 'services';

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    // Accessors
    public function getMediaUrlAttribute(): string
    {
        // If media_path already looks like a URL, return it; otherwise treat as public asset path
        $path = $this->media_path ?? '';
        if (preg_match('/^https?:\\/\\//i', $path)) {
            return $path;
        }
        return asset($path);
    }

    public function getPosterUrlAttribute(): ?string
    {
        $path = $this->poster_image;
        if (!$path) {
            return null;
        }
        if (preg_match('/^https?:\\/\\//i', $path)) {
            return $path;
        }
        return asset($path);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSection($query, string $section)
    {
        return $query->where('section', $section);
    }
}
