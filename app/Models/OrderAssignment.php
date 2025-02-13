<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderAssignment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
