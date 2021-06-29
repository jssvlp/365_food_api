<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $table = 'productos';
    protected $primaryKey = 'idproducto';

    protected $casts = [
        'codfamilia' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class,'codfamilia','codfamilia');
    }
}
