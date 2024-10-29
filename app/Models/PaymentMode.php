<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMode extends Model
{
    use HasFactory;

    protected $table = 'mode_of_payment';
    protected $guarded = [];

    public function scopeActive($query){
        return $query->where('status', 'ACTIVE')
            ->select('id','mode_of_payment');
    }

    public function scopeGetById($query, $id){
        return $query->whereIn('id', json_decode($id))
            ->where('status', 'ACTIVE')
            ->select('id','mode_of_refund')
            ->orderBy('mode_of_refund', 'ASC');
    }
}
