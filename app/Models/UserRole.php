<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class UserRole extends Model
{
    use HasFactory;
    protected $fillable = [
        'parent_user_id',
        'user_id',
        'role_id',
        'created_by',
        'modified_by',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function parentUser()
    {
        return $this->belongsTo(User::class, 'parent_user_id');
    }
}

?>
