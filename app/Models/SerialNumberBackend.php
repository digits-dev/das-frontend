<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerialNumberBackend extends Model
{
    use HasFactory;

    protected $connection = 'backend';
    protected $table = 'returns_serial_retail';
    protected $fillable = [
        'returns_header_id',
        'returns_body_item_id',
        'serial_number',
        'created_at'
    ];

}