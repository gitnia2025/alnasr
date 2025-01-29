<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
class Doc extends Model
{
    use HasFactory;

    protected $fillable = [
        'img', 'name_ar', 'name_en', 'specialist_ar', 'specialist_en', 'desc_ar', 'desc_en',
        'phone', 'whats', 'age', 'sex', 'day_en', 'day_ar', 'time_from', 'time_to', 'floor',
        'certificate_ar', 'certificate_en', 'exp_ar', 'exp_en', 'cv', 'active', 'show', 'specialist_id'
    ];

    protected $casts = [
        'certificate_ar' => 'array',
        'certificate_en' => 'array',
        'exp_ar' => 'array',
        'exp_en' => 'array',
    ];

    // العلاقة مع التخصص
    public function specialist()
    {
        return $this->belongsTo(Specialist::class);
    }

    // العلاقة مع العروض
    public function offers()
    {
        return $this->hasMany(Offer::class);
    }
}
