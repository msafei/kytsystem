<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KytSign extends Model
{
    use HasFactory;

    protected $fillable = [
        'kyt_report_id', 'user_id', 'signEncryp'
    ];

    public function kytReport()
    {
        return $this->belongsTo(KytReport::class, 'kyt_report_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
