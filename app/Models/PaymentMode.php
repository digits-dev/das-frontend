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
}
