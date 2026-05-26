<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_id',
        'product_id',
        'price_per_day',
        'quantity',
        'subtotal'
    ];

    protected $casts = [
        'price_per_day' => 'decimal:2',
        'quantity' => 'integer',
        'subtotal' => 'decimal:2'
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
