<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lab extends Model
{
    use HasFactory;
    protected $table = 'lab'; 

    protected $fillable = [
        'name_ar', 'name_en',
        'phone', 'whats','day_en', 'day_ar', 'time_from', 'time_to'
    ];
}
