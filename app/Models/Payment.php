<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_id',
        'amount',
        'payment_type',
        'transaction_id',
        'status',
        'payment_proof',
        'raw_payload'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'raw_payload' => 'array'
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}
