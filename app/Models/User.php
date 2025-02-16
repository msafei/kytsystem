<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'employee_id',
        'status_id',
        'username',
        'password',
        'role',
        'remember_token'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }


}

