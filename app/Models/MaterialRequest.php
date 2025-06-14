<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class MaterialRequest extends Model
{
    use HasFactory, LogsActivity;
    protected $fillable = [
        'mr_number',
        'date',
        'warehouse_id',
        'requested_by',
        'status_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logFillable();
    }

    public function items()
    {
        return $this->hasMany(MaterialRequestItem::class, 'mr_id');
    }
    
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    public function requestor()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
    public static function generateAutoNumber($format, $warehouseId, $type)
    {
        // Start a transaction
        DB::beginTransaction();

        try {
            // Use a row-level lock to prevent race conditions
            $tracker = DB::table('sequence_trackers')
                ->where('warehouse_id', $warehouseId)
                // ->where('sequence_format', $format)
                ->where('type', $type)
                ->lockForUpdate() // Lock the row for update
                ->first();

            if (!$tracker) {
                // If no tracker exists, create one with the initial sequence number
                $trackerId = DB::table('sequence_trackers')->insertGetId([
                    'warehouse_id' => $warehouseId,
                    'sequence_format' => $format,
                    'type' => $type,
                    'last_number' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $tracker = (object)[
                    'id' => $trackerId,
                    'last_number' => 0,
                ];
            }

            // Increment the sequence number
            $seq_num = $tracker->last_number + 1;
            DB::table('sequence_trackers')
                ->where('id', $tracker->id)
                ->update(['last_number' => $seq_num]);

            // Commit the transaction
            DB::commit();

            // Format the sequence number
            $seq_num = str_pad($seq_num, 4, '0', STR_PAD_LEFT);
            $currentMonth = Carbon::now()->format('m');
            $currentYear4 = Carbon::now()->format('Y');
            $currentYear = Carbon::now()->format('y');
            $format = str_replace('[MONTH]', $currentMonth, $format);
            $format = str_replace('[YEAR]', $currentYear, $format);
            $format = str_replace('[YEAR4]', $currentYear4, $format);
            $format = str_replace('[CNT]', $seq_num, $format);

            return $format;
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();
            Log::error('Error generating auto number: ' . $e->getMessage());
            throw $e; // Re-throw the exception to handle it in the caller
        }
    }

}
