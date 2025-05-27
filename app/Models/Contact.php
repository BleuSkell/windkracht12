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
        'name',
        'email',
        'phone',
        'message',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'contactId');
    }
}
