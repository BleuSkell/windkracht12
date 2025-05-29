<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Instructor;
use App\Models\User;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'instructor_customers', 
            'instructorId', 'customerId')->withTimestamps();
    }
}