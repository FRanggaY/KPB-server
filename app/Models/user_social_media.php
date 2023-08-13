<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_social_media extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $hidden = [
        'id',
        'user_id'
    ];

    protected $fillable = [
        'facebook',
        'instagram',
        'twitter',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
