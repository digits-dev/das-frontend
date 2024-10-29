<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $connection = 'backend';
    protected $table = 'brand';
    protected $guarded = [];

    public function scopeGetAll($query){
        return $query->select('id', 'brand_description')
            ->orderBy('brand_description', 'ASC');
    }
}
