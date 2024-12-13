<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintainer extends Model
{
    use HasFactory;

    protected $table = 'maintainers';

    protected $fillable = [
        'user_id',
        'property_id',
        'maintenance_type',
        'description',
        'additional_info',
        'created_by',
        'modified_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
