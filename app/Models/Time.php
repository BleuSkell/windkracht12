<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Date;

class Time extends Model
{
    /** @use HasFactory<\Database\Factories\TimeFactory> */
    use HasFactory;

    protected $table = 'times';

    protected $fillable = [
        'dateId',
        'time',
    ];

    public function dates()
    {
        return $this->HasMany(Date::class, 'dateId');
    }
}
