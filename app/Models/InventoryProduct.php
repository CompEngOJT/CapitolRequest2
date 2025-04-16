<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class InventoryProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'total_stocks',
        'last_withdrawal',
        'stock_remaining'
    ];
    
}