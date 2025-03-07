<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriversList extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'drivers_list_table';

    // Specify the fillable fields
    protected $fillable = [
        'first_name',
        'last_name',
        'position',
        'status',
    ];
}
