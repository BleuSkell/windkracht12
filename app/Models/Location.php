<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    /** @use HasFactory<\Database\Factories\LocationFactory> */
    use HasFactory;

    protected $table = 'locations';

    protected $fillable = [
        'name',
        'address',
        'city',
        'postalCode'
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'locationId');
    }
}
