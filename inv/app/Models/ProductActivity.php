<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductActivity extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'product_id',
        'product_name',
        'action',
        'description',
    ];

    // If you want to add relationships (optional & safe since no FK constraints)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
