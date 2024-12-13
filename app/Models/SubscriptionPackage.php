<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class SubscriptionPackage extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'amount',
        'interval',
        'staff_limit',
        'property_limit',
        'tenant_limit',
        'created_by',
        'modified_by',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function transactions()
    {
        return $this->hasMany(SubscriptionTransaction::class, 'package_id');
    }
}
?>