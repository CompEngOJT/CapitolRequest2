<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'equipment';

    // Specify the fillable fields
    protected $fillable = [
        'name',
    ];
}
