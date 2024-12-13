<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $table = 'property';
    protected $fillable = [
        'type',
        'name',
        'description',
        'address',
        'country',
        'state',
        'city',
        'post_code',
        'image',
        'created_by',
        'modified_by',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function units()
    {
        return $this->hasMany(PropertyUnit::class);
    }

    public function leases()
    {
        return $this->hasMany(Lease::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function maintainers()
    {
        return $this->hasMany(Maintainer::class);
    }
}
