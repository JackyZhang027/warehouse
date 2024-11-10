<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'name', 'category_id', 'uom_id', 'description'];
    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'category_id');
    }
    public function uom()
    {
        return $this->belongsTo(UOM::class, 'uom_id');
    }
}
