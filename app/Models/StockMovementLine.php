<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovementLine extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'stock_movement_id',
        'item_id',
        'qty',
        'remarks',
    ];

    public function stockMovement()
    {
        return $this->belongsTo(StockMovement::class, 'stock_movement_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function warehouseItem()
    {
        return $this->belongsTo(WarehouseItem::class, 'item_id', 'item_id');
    }
}
