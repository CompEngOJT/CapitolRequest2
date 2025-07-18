<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers_list_table', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('first_name'); // First Name
            $table->string('last_name'); // Last Name
            $table->string('position'); // Position
            $table->enum('status', ['active', 'inactive']); // Status (Active, Inactive)
            $table->timestamps(); // Created_at and Updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drivers_list_table');
    }
}
