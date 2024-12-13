<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'logo',
        'landing_page_logo',
        'created_by',
        'modified_by',
    ];

    protected $dates = ['created_at', 'updated_at'];
}
