<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    protected $fillable = [
        'iduser',
        'idcountry',
        'idstate',
        'idcities',
        'name',
        'notelp',
        'gender',
        'email',
        'password',
        'role',
        'address',
        'address_2',
        'portal_code',
        'photo',
        'status',
        'created_at',
        'updated_at'
    ];
}
