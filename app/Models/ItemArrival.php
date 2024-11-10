<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemArrival extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    
    public function deliveryOrderItem()
    {
        return $this->belongsTo(DeliveryOrderItem::class, 'delivery_order_item_id');
    }
}
