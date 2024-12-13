<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $fillable = [
        'role_id',
        'permission',
        'created_by',
        'modified_by',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
