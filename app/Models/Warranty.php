<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warranty extends Model
{
    use HasFactory;

    protected $table = 'returns_header_retail';
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
        return $this->hasMany(WarrantyLine::class, 'returns_header_id', 'id');
    }
}
