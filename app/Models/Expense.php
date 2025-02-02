<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Expense extends Model
{

    use HasFactory;
    use SoftDeletes;
    
    protected $guarded = [];

    public function addExpenses()
    {
        return $this->hasMany(AddExpenses::class, 'category_id', 'id');
    }

    
}

