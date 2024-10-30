<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WarrantyOnlineLine extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'returns_body_item';
    protected $fillable = [
        'returns_header_id',
        'digits_code',
        'item_description',
        'brand',
        'cost',
        'quantity',
        'problem_details',
        'problem_details_other',
        'serialize',
        'created_at'
    ];

    public function serials():HasMany{
        return $this->hasMany(WarrantyOnlineSerial::class, 'returns_body_item_id','id');
    }

    public function item():BelongsTo{
        return $this->belongsTo(Item::class, 'digits_code', 'digits_code');
    }
}
