<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialist extends Model
{
    use HasFactory;

    protected $fillable = ['name_ar', 'name_en'];

    // العلاقة مع الأطباء (دكتور واحد له تخصص واحد)
    public function docs()
    {
        return $this->hasMany(Doc::class);
    }
}
