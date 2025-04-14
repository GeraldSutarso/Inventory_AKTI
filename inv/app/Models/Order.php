<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'vendor_tmmin',
        'vendor_akti',
        'keterangan',
        'user_id',
        'kaunit_id',
    ];

    // Relasi ke Product (jika perlu)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke User (jika perlu)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
