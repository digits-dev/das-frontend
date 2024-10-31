<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WarrantyOnline extends Model
{
    use HasFactory;

    protected $table = 'returns_header';
    protected $fillable = [
        'returns_status',
        'returns_status_1',
        'return_reference_no',
        'purchase_location',
        'store',
        'customer_last_name',
        'customer_first_name',
        'address',
        'email_address',
        'contact_no',
        'order_no',
        'purchase_date',
        'mode_of_payment',
        'mode_of_refund',
        'items_included',
        'items_included_others',
        'mode_of_return',
        'store_dropoff',
        'branch',
        'branch_dropoff',
        'created_at',
        'transaction_type'
    ];

    public function lines() : HasMany{
        return $this->hasMany(WarrantyOnlineLine::class, 'returns_header_id', 'id');
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         // Get the most recent reference number or start at 1 if none exists
    //         $latestRef = WarrantyOnline::selectRaw('SUBSTR(return_reference_no, 1, 8) AS refno')
    //             ->orderByDesc('refno')
    //             ->first();

    //         $numeric = $latestRef ? (int) $latestRef->refno + 1 : 1;

    //         // Loop to create a unique tracking number and check for duplicates
    //         $i = 0;
    //         do {
    //             $numberCode = str_pad($numeric + $i, 8, "0", STR_PAD_LEFT);
    //             $trackingNumber = $numberCode . 'E';
    //             $i++;

    //             // Check for existence in both tables
    //             $existsInReturnsBackend = WarrantyOnlineBackend::whereRaw('SUBSTR(return_reference_no, 1, 8) = ?', [$numberCode])
    //                 ->exists();

    //             $existsInReturnsFrontend = WarrantyOnline::whereRaw('SUBSTR(return_reference_no, 1, 8) = ?', [$numberCode])
    //                 ->exists();

    //         } while ($existsInReturnsBackend || $existsInReturnsFrontend);

    //         // Set the generated return_reference_no
    //         $model->return_reference_no = $trackingNumber;
    //     });
    // }
}
