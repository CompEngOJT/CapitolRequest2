<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChiefsTable extends Migration
{
    public function up()
    {
        Schema::create('chiefs', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique(); // Unique username
            $table->string('password'); // Password (will be hashed)
            $table->string('theme')->default('light'); // Theme preference (default: light)
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('chiefs');
    }
}