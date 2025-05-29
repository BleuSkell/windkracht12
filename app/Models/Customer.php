<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Instructor;
use App\Models\User;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function instructors()
    {
        return $this->belongsToMany(Instructor::class, 'instructor_customers', 
            'customerId', 'instructorId')->withTimestamps();
    }
}