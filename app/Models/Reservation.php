<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Date;
use App\Models\Payment;

class Reservation extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationFactory> */
    use HasFactory;

    protected $table = 'reservations';

    protected $fillable = [
        'userId',
        'dateId',
        'paymentId',
    ];

    public function users()
    {
        return $this->BelongsTo(User::class, 'userId');
    }

    public function dates()
    {
        return $this->BelongsTo(Date::class, 'dateId');
    }

    public function payments()
    {
        return $this->BelongsTo(Payment::class, 'paymentId');
    }
}
