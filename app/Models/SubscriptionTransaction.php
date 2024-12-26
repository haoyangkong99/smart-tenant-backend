<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class SubscriptionTransaction extends Model
{
    use HasFactory;
    protected $table = 'subscription_transactions';
    protected $fillable = [
        'user_id',
        'package_id',
        'amount',
        'payment_type',
        'payment_status',
        'receipt',
        'created_by',
        'modified_by',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function package()
    {
        return $this->belhongsTo(SubscriptionPackage::class, 'package_id');
    }
}
?>
