<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemOut extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function details()
    {
        return $this->hasMany(ItemOutDetail::class);
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    public function createUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
