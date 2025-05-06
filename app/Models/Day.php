<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Date;

class Day extends Model
{
    /** @use HasFactory<\Database\Factories\DayFactory> */
    use HasFactory;

    protected $table = 'days';

    protected $fillable = [
        'dateId',
        'day',
    ];

    public function dates()
    {
        return $this->HasMany(Date::class, 'dateId');
    }
}
