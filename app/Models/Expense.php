<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $table = 'expenses';

    protected $fillable = [
        'property_id',
        'unit_id',
        'receipt_number',
        'receipt_date',
        'expense_type',
        'total_amount',
        'remarks',
        'attachment',
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
}