<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'userId',
        'roleName',
    ];

    public function users()
    {
        return $this->HasOne(User::class, 'userId');
    }
}
