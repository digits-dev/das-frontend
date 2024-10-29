<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $table = 'refprovince';
    protected $guarded = [];

    public function scopeGetAll($query){
        return $query->select('provCode','provDesc')
            ->orderBy('provDesc','asc');
    }

    public function scopeGetByCode($query, $provCode){
        return $query->where('provCode',$provCode)->first();
    }
}
