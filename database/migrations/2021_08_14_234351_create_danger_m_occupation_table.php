<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDangerMOccupationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('danger_m_occupation', function (Blueprint $table) {
            $table->foreignId('danger_id')->constrained();
            $table->foreignId('m_occupation_id')->constrained();

            $table->primary(['danger_id', 'm_occupation_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('danger_m_occupation');
    }
}
