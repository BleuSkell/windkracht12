<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Contact extends Model
{
    /** @use HasFactory<\Database\Factories\ContactFactory> */
    use HasFactory;

    protected $table = 'contacts';

    protected $fillable = [
        'userId',
        'firstName',
        'infix',
        'lastName',
        'adress',
        'city',
        'dateOfBirth',
        'bsnNumber',
        'mobile',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
