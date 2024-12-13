<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'property_id',
        'unit_id',
        'invoice_number',
        'invoice_month',
        'invoice_end_date',
        'total_amount',
        'remarks',
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

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}