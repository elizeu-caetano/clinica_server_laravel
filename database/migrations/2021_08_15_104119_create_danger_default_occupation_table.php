<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDangerDefaultOccupationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('danger_default_occupation', function (Blueprint $table) {
            $table->foreignId('danger_id')->constrained();
            $table->foreignId('default_occupation_id')->constrained();

            $table->primary(['danger_id', 'default_occupation_id'], 'custom_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('danger_default_occupation');
    }
}
