<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'companyType', 'defaultRole'];

    // Label untuk Company Type (1 = Main Company, 2 = Outsourcing)
    public function getCompanyTypeLabelAttribute()
    {
        return $this->companyType == 1 ? 'Main Company' : 'Outsourcing';
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'position_id');
    }
    

}
