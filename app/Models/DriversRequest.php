<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverRequest extends Model
{
    use HasFactory;

    protected $table = 'driver_requests'; // Make sure this matches your actual table name
    
    protected $fillable = [
        'id',
        'driver_name',
        'date',
        'type', // This should correspond to product_name in inventory
        'quantity',
        'goverment_car_used',
        'government_car_number',
        'place_to_visit',
        'purpose',
        'requested_by',
        'division',
        'status',
    
    ];
}