<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreationTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['creation_id', 'locale', 'name', 'slug', 'description'];

    public function creation(): BelongsTo
    {
        return $this->belongsTo(Creation::class);
    }
}
