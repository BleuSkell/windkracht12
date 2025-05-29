<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationFactory> */
    use HasFactory;

    protected $table = 'reservations';

    protected $fillable = [
        'userId',
        'packageId',
        'locationId',
        'reservationDate',
        'reservationTime',
        'duoPartnerName',
        'duoPartnerEmail',
        'duoPartnerAddress',
        'duoPartnerCity',
        'duoPartnerPhone',
        'cancellationReason',
        'cancellationStatus',
        'originalDate',
        'originalTime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'packageId');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'locationId');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'reservationId');
    }
}
