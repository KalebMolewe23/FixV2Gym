<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member_gym extends Model
{
    use HasFactory;
    protected $table = 'member_gym';

    // Kolom yang dapat diisi
    protected $fillable = [
    'iduser', 
    'idmember', 
    'idpacket_trainer',
    'idpaket',
    'total_price',
    'start_training',
    'end_training',
    'created_at',
    'updated_at'
    ];
}
