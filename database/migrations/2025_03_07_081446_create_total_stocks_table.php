<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTotalStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('total_stocks_table', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('product_name')->unique(); // Product name (e.g., Engine Oil, Gear Oil)
            $table->integer('total_stocks'); // Total stock quantity
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
        Schema::dropIfExists('total_stocks_table');
    }
}