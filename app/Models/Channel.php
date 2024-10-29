<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected $table = 'channels';
    protected $guarded = [];

    const RETAIL = 6;

    public function scopeActive($query){
        return $query->where('channel_status', 'ACTIVE')
            ->select('id','channel_name')
            ->orderBy('channel_name', 'ASC');
    }

    public function scopeGetChannelById($query, $id){
        return $query->where('id',$id)
            ->where('channel_status','=','ACTIVE')
            ->first();
    }
}
