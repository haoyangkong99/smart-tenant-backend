<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'url',
        'phone',
        'address',
        'remark',
        'created_by',
        'modified_by',
    ];

    protected $dates = ['created_at', 'updated_at'];
}
