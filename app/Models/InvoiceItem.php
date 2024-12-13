<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $table = 'invoice_items';

    protected $fillable = [
        'invoice_id',
        'item_name',
        'amount',
        'description',
        'created_by',
        'modified_by',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
