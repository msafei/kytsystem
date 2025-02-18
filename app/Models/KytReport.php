<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KytReport extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'company_id', 'departement_id', 'projectTitle', 'instructors', 'attendants', 'status'];

    protected $casts = [
        'instructors' => 'json',
        'attendants' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'departement_id');
    }
}
