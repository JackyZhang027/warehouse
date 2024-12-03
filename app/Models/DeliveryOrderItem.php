<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryOrderItem extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logFillable();
    }
    public function deliveryOrder()
    {
        return $this->belongsTo(DeliveryOrder::class, 'delivery_order_id');
    }
    public function warehouse()
    {
        return $this->hasOneThrough(Warehouse::class, DeliveryOrder::class, 'id', 'id', 'delivery_order_id', 'warehouse_id');
    }

    public function materialRequestItem()
    {
        return $this->belongsTo(MaterialRequestItem::class, 'material_request_item_id');
    }

}
