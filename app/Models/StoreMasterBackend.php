<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreMasterBackend extends Model
{
    use HasFactory;

    protected $connection = 'backend';
    protected $table = 'stores';
    protected $guarded = [];

    public function scopeGetCustomerLocationByBranchId($query, $storeFrontId, $branch){
        return $query->where('branch_id', $branch)
            ->where('stores_frontend_id', $storeFrontId)
            ->where('store_status', 'ACTIVE')
            // ->where('store_dropoff_privilege', 'YES')
            ->orderBy('branch_id', 'ASC');
    }
}
