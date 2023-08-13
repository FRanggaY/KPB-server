<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class profile_desc extends Model
{
    use HasFactory;

    protected $fillable = [
        'vision',
        'mission',
        'description',
        'image'
    ];
}
