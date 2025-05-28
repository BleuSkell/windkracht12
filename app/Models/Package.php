<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    /** @use HasFactory<\Database\Factories\PackageFactory> */
    use HasFactory;

    protected $table = 'packages';

    protected $fillable = [
        'name',
        'description', 
        'price',
        'numberOfLessons',
        'isDuo'
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'packageId');
    }
}
