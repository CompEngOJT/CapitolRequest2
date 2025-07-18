<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DriverRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_request_table', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('driver_name'); // Driver's Full Name
            $table->date('date'); // Date
            $table->string('type'); // Type (Fuel, Break Fluid, Gasoline)
            $table->integer('quantity'); // Quantity
            $table->string('measurement'); // Measurement (Liters, Gallons, mL)
            $table->string('government_car_used'); // Government Car Used
            $table->string('government_car_number'); // Government Car Number
            $table->string('place_to_visit'); // Place to Visit
            $table->string('purpose'); // Purpose
            $table->string('requested_by'); // Requested By
            $table->string('division'); // Division
            $table->string('status')->default('Under Review'); // Status with default value
            $table->unsignedBigInteger('product_id'); // Foreign key to total_stocks_table
            $table->foreign('product_id')->references('id')->on('total_stocks_table')->onDelete('cascade');
            $table->timestamps(); // Created at and Updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('driver_request_table');
    }
}