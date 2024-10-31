<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreMaster extends Model
{
    use HasFactory;

    protected $table = 'stores';
    protected $guarded = [];

    public function scopeGetAll($query){
        return $query->where('store_status','ACTIVE')
            ->orderBy('store_name', 'ASC');
    }

    public function scopeGetByChannel($query, $channel){
        return $query->where('channels_id',$channel)
            ->where('store_status','ACTIVE')
            ->where('store_name','!=','SERVICE CENTER')
            ->orderBy('store_name', 'ASC');
    }

    public function scopeGetByName($query, $channel, $storeName){
        return $query->where('store_name', $storeName)
            ->where('channels_id', $channel)
            ->where('store_status', 'ACTIVE');
    }

    public function scopeGetByStoreDropOff($query, $storeName){
        return $query->where('store_name', $storeName)
            ->where('channels_id', 6)//retail
            ->where('store_status', 'ACTIVE');
    }

    public function backend():BelongsTo{
        return $this->belongsTo(StoreDropOff::class,'id','stores_frontend_id');
    }
}
