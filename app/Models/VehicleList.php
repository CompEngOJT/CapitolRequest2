<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleList extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'vehicle_list_table';

    // Specify the fillable fields
    protected $fillable = [
        'equipment_type',
        'unit',
    ];
}
