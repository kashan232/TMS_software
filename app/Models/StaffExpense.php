<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffExpense extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];


    // StaffExpense.php (Model)
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
