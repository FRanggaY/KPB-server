<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_position extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_kpb',
        'position_department',
        'user_id'
    ];

    protected $hidden = [
        'id',
        'user_id'
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
