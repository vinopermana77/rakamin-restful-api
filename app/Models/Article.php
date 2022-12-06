<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'image',
        'user_id',
        'category_id'
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Categories::class, 'category_id', 'id');
    }
}
