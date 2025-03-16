<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'expense_category_id',
        'name',
        'image',
        'Amount',
        'expense_details',
        'is_stock',
    ];

    // Relationship with ExpenseCategory Model
    public function ExpenseCategory()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id', 'id');
    }
}