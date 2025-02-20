<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlowKytReport extends Model
{
    use HasFactory;

    protected $fillable = ['flowStatus', 'companyType', 'position_id'];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    // Relasi dengan KYT Report
    public function kytReports()
    {
        return $this->hasMany(KytReport::class);
    }
}
