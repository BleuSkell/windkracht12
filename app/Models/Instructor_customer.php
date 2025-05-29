<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Instructor;
use App\Models\Customer;

class InstructorCustomer extends Pivot
{
    use HasFactory;

    protected $table = 'instructor_customer';

    protected $fillable = [
        'instructorId',
        'customerId'
    ];

    public function instructor()
    {
        return $this->belongsToMany(Customer::class, 'instructor_customers', 
            'instructorId', 'customerId')
            ->using(InstructorCustomer::class)
            ->withTimestamps();
    }

    public function customer()
    {
        return $this->belongsToMany(Instructor::class, 'instructor_customers', 
            'customerId', 'instructorId')
            ->using(InstructorCustomer::class)
            ->withTimestamps();
    }
}