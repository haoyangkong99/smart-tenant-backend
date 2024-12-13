<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    use HasFactory;

    protected $table = 'maintenance_requests';

    protected $fillable = [
        'property_id',
        'unit_id',
        'maintainer_id',
        'issue_type',
        'status',
        'issue_attachment',
        'created_by',
        'upmodified_bydated',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function unit()
    {
        return $this->belongsTo(PropertyUnit::class);
    }

    public function maintainer()
    {
        return $this->belongsTo(Maintainer::class);
    }
}