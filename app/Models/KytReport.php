<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KytReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'company_id', 'department_id', 'date', 'projectTitle', 'workingStart', 'workingEnd',
        'shift','instructors', 'attendants', 'potentialDangerous', 'mostDanger', 'countermeasures', 'keyWord',
        'reviewedBy', 'approvedBy1', 'approvedBy2', 'status'
    ];

    protected $casts = [
        'instructors' => 'array',
        'attendants' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
