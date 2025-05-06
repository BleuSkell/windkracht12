<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Day;
use App\Models\Time;
use App\Models\Reservation;

class Dates extends Model
{
    /** @use HasFactory<\Database\Factories\DatesFactory> */
    use HasFactory;

    protected $table = 'dates';

    protected $fillable = [
        'dayId',
        'timeId',
    ];

    public function days()
    {
        return $this->BelongsTo(Day::class, 'dayId');
    }

    public function times()
    {
        return $this->BelongsTo(Time::class, 'timeId');
    }

    public function reservations()
    {
        return $this->HasOne(Reservation::class, 'dateId');
    }
}
