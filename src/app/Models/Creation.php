<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Creation extends Model
{
    use HasFactory;

    protected $fillable = ['size', 'price', 'download', 'category_id', 'material_id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(CreationTranslation::class);
    }

    public function translation(?string $locale = null): HasOne
    {
        return $this->hasOne(CreationTranslation::class)
            ->where('locale', $locale ?? app()->getLocale());
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(GalleryImage::class)->orderBy('ranking');
    }

    public function coverImage(): HasOne
    {
        return $this->hasOne(GalleryImage::class)->orderBy('ranking');
    }
}
