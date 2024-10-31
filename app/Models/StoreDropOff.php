<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class StoreDropOff extends Model
{
    use HasFactory;

    protected $table = 'stores_backend';
    protected $guarded = [];

    public function scopeGetAll($query) {
        return $query->join('stores', 'stores_backend.stores_frontend_id','=', 'stores.id')
        ->where('stores_backend.store_status','=','ACTIVE')
        ->where('stores_backend.channels_id', 6)
        ->where('store_dropoff_privilege','YES')
        ->select('stores.store_name as store_drop_off_name')
        ->orderBy('stores.store_name', 'ASC')
        ->groupby('stores.store_name');
    }

    public function scopeGetBackendStores($query) {
        return $query->where('store_status', 'ACTIVE')
            ->orderBy('branch_id', 'ASC');
    }

    public function scopeGetBackendStoresById($query, $storeFront){
        return $query->where('channels_id', '!=', 7)
            ->where('stores_frontend_id', $storeFront)
            ->where('store_status', 'ACTIVE')
            ->select(DB::raw('DISTINCT branch_id, COUNT(*) AS count_pid'))
            ->distinct('stores_frontend_id')
            ->groupBy('branch_id')
            ->orderBy('branch_id', 'ASC');
    }

    public function scopeGetBranchDropOffById($query, $storeFront){
        return $query->where('channels_id', '!=', 7)
            ->where('stores_frontend_id', $storeFront)
            ->where('store_status', 'ACTIVE')
            ->orderBy('branch_id', 'ASC');
    }

    public function scopeGetCustomerLocationByBranchId($query, $storeFrontId, $branch){
        return $query->where('branch_id', $branch)
            ->where('stores_frontend_id', $storeFrontId)
            ->where('store_status', 'ACTIVE')
            ->where('store_dropoff_privilege', 'YES')
            ->orderBy('branch_id', 'ASC');
    }

    public function frontend():BelongsTo{
        return $this->belongsTo(StoreMaster::class,'stores_frontend_id','id');
    }

}
