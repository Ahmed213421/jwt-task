<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['specialist_id','title','price','duration'];

    public function specialist() {
        return $this->belongsTo(Specialist::class);
    }
}

