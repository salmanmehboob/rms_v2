<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'item_category_id',
        'name',
        'image',
        'quantity',
        'cost_price',
        'retail_price',
        'status',
    ];

    // Relationship with ItemCategory Model
    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id');
    }
}