<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WarrantyDistributionLine extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'returns_body_item';

    public function serials():HasMany{
        return $this->hasMany(WarrantyDistributionSerial::class, 'returns_body_item_id','id');
    }

    public function items():BelongsTo{
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
