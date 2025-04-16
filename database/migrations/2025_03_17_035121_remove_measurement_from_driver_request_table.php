<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveMeasurementFromDriverRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('driver_request_table', function (Blueprint $table) {
            $table->dropColumn('measurement'); // Remove the measurement column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('driver_request_table', function (Blueprint $table) {
            $table->string('measurement')->after('quantity'); // Add the measurement column back
        });
    }
}