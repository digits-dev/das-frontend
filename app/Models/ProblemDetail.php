<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProblemDetail extends Model
{
    use HasFactory;

    protected $table = 'srof_problem_details';
    protected $guarded = [];

    public function scopeActive($query){
        return $query->where('status', 'ACTIVE')
            ->select('id','problem_details');
    }
}
