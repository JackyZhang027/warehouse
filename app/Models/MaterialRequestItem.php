<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class MaterialRequestItem extends Model
{
    use HasFactory, LogsActivity;
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
    protected $casts = [
        'date_needed' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logFillable();
    }

    public function materialRequest()
    {
        return $this->belongsTo(MaterialRequest::class, 'mr_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }


}
