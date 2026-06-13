<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'start_at',
        'end_at',
        'total_days',
        'total_price',
        'status',
        'note',
        'admin_note',
        'shipping_address',
        'phone',
        'ktp_name',
        'nik',
        'ktp_photo',
        'payment_method',
        'payment_status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'total_days' => 'integer',
        'total_price' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(RentalItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
