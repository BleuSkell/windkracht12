<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reservation;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'paymentDate',
        'paymentMethod',
        'amount',
        'status',
    ];

    public function reservations()
    {
        return $this->HasOne(Reservation::class, 'paymentId');
    }
}
