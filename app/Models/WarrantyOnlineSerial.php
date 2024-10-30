<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyOnlineSerial extends Model
{
    use HasFactory;

    protected $table = 'returns_serial';
    protected $fillable = [
        'returns_header_id',
        'returns_body_item_id',
        'serial_number',
        'created_at'
    ];
}
