<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_additional extends Model
{
    use HasFactory;

    protected $table = "user_additionals";

    protected $fillable = [
        'gender',
        'nik',
        'nip',
        'work_unit',
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
