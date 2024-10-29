<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'refcitymun';
    protected $guarded = [];

    public function scopeGetAll($query){
        return $query->orderBy('citymunDesc', 'ASC');
    }

    public function scopeGetByProvince($query, $province){
        return $query->where('provCode',$province)
            ->orderBy('citymunDesc', 'ASC');
    }
}
