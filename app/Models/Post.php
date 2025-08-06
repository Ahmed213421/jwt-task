<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'contact_phone',
        'admin_id'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class,'admin_id');
    }
}
