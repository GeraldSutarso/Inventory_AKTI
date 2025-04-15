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
        'is_approved',
        'is_rejected',
        'is_acknowledged',
    ];

    // ======================
    // RELATIONSHIPS
    // ======================

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user() // The user who made the order
    {
        return $this->belongsTo(User::class);
    }

    public function kaunit() // The head of unit (kaunit) who approves/rejects
    {
        return $this->belongsTo(User::class, 'kaunit_id');
    }

    // ======================
    // SCOPES (Optional)
    // ======================

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true)->where('is_rejected', false);
    }

    public function scopeRejected($query)
    {
        return $query->where('is_rejected', true);
    }

    public function scopeAcknowledged($query)
    {
        return $query->where('is_acknowledged', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_approved', false)->where('is_rejected', false);
    }
}
