<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lease extends Model
{
    use HasFactory;

    protected $table = 'leases';

    protected $fillable = [
        'property_id',
        'unit_id',
        'tenant_id',
        'lease_number',
        'rent_start_date',
        'rent_end_date',
        'rent_amount',
        'rent_type',
        'terms',
        'deposit_amount',
        'deposit_description',
        'contract',
        'status',
        'created_by',
        'modified_by',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function unit()
    {
        return $this->belongsTo(PropertyUnit::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
