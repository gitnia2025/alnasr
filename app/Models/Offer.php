<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;   
     protected $table = 'offers'; 

    protected $fillable = ['img', 'doc_id'];

    // العلاقة مع الدكتور
    public function doc()
    {
        return $this->belongsTo(Doc::class);
    }
}
