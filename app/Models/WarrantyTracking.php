<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarrantyTracking extends Model
{
    use HasFactory;

    protected $table = 'returns_tracking_status';
    protected $primary = 'return_reference_no';
    protected $fillable = [
        'return_reference_no',
        'returns_status'
    ];

    public function headerRetail():BelongsTo{
        return $this->belongsTo(Warranty::class,'return_reference_no','return_reference_no')->select(['return_reference_no']);
    }

    public function headerDistri():BelongsTo{
        return $this->belongsTo(WarrantyOnline::class,'return_reference_no','return_reference_no')->select(['return_reference_no']);
    }

    public function status():BelongsTo{
        return $this->belongsTo(WarrantyStatus::class, 'returns_status','id')->select(['id','warranty_status']);
    }

    public function scopeGetTrackingTimeline($query, $referenceNumber){
        return $query->join('warranty_statuses','returns_tracking_status.returns_status', '=', 'warranty_statuses.id')
        ->where('returns_tracking_status.return_reference_no', $referenceNumber)
        ->select(
            'returns_tracking_status.return_reference_no',
            'warranty_statuses.warranty_status',
            'returns_tracking_status.created_at as date'
        )
        ->orderBy('returns_tracking_status.created_at', 'DESC')
        ->get();
    }
}
