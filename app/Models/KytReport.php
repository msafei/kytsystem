<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KytReport extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'company_id', 'departement_id', 'projectTitle', 'instructors', 'attendants', 'status'];

    protected $casts = [
        'instructors' => 'array',
        'attendants' => 'array',
    ];

    // Relasi dengan user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // Relasi dengan department
    public function department()
    {
        return $this->belongsTo(Department::class, 'departement_id');
    }
}
