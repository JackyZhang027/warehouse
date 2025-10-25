<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'movement_type',
        'warehouse_from_id',
        'warehouse_to_id',
        'created_by',
    ];
    
    public function getMovementTypeLabelAttribute()
    {
        return match($this->movement_type) {
            'in' => 'Masuk',
            'out' => 'Keluar',
            'transfer' => 'Transfer',
            default => ucfirst($this->movement_type),
        };
    }

    public function lines()
    {
        return $this->hasMany(StockMovementLine::class, 'stock_movement_id');
    }

    public function warehouseFrom()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_from_id');
    }

    public function warehouseTo()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_to_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}