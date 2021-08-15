<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultOccupationProcedureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_occupation_procedure', function (Blueprint $table) {
            $table->foreignId('m_occupation_id')->constrained();
            $table->foreignId('procedure_id')->constrained();

            $table->primary(['m_occupation_id', 'procedure_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_occupation_procedure');
    }
}
