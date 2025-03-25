<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'stock',
        'stock_min',
        'stock_max',
        'price',
        'image',
        'qr_code',
    ];

    public function category () {
        return $this->belongsTo(Category::class);
    }
    public function generateQrCode()
    {
        return QrCode::size(200)->generate(route('products.show', $this->id));
    }
    public function supplies()
    {
        return $this->hasMany(ProductSupplies::class);
    }
}


