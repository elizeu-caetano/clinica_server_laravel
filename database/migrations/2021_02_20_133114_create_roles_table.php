<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid'); 
            $table->string('name', 100);     
            $table->string('description', 255)->nullable();     
            $table->boolean('active')->default(true);
            $table->boolean('admin')->default(false);
            $table->boolean('deleted')->default(false);
            $table->foreignId('contractor_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
