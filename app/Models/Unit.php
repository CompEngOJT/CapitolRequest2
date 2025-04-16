<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'units';

    // Specify the fillable fields
    protected $fillable = [
        'name',
    ];
}
