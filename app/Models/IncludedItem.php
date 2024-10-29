<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncludedItem extends Model
{
    use HasFactory;

    protected $table = 'items_included';
    protected $guarded = [];

    public function scopeActive($query){
        return $query->where('status', 'ACTIVE')
            ->select('id','items_description_included');
    }
}
