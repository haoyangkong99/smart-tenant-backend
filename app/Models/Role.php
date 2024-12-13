<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'created_by',
        'modified_by',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function permissions()
    {
        return $this->hasMany(RolePermission::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_permissions', 'role_id', 'id');
    }
}
