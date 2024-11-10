<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function DeliveryItems()
    {
        return $this->hasMany(DeliveryOrderItem::class, 'delivery_order_id');
    }

    public function createUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}
