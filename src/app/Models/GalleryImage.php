<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryImage extends Model
{
    use HasFactory;

    protected $fillable = ['creation_id', 'filename', 'ranking'];

    public function creation(): BelongsTo
    {
        return $this->belongsTo(Creation::class);
    }
}
