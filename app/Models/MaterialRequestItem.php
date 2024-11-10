<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialRequestItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'mr_id',
        'item_id',
        'qty',
        'do_qty',
        'received_qty',
        'date_needed',
        'boq_code',
        'check_m',
        'check_t',
        'check_he',
        'check_c',
        'check_o',
        'description',
    ];

    public function materialRequest()
    {
        return $this->belongsTo(MaterialRequest::class, 'mr_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }


}
