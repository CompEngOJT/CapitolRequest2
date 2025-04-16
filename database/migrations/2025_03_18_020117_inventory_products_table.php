<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InventoryProductsTable extends Migration
{
    public function up()
    {
        Schema::create('inventory_products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->integer('total_stocks');
            $table->integer('last_withdrawal')->default(0);
            $table->integer('stock_remaining');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_products');
    }
}