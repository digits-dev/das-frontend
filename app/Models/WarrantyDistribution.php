<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WarrantyDistribution extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'returns_header';

    public function lines() : HasMany{
        return $this->hasMany(WarrantyDistributionLine::class, 'returns_header_id', 'id');
    }
}
