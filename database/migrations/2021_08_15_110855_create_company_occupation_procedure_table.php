<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyOccupationProcedureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_occupation_procedure', function (Blueprint $table) {
            $table->foreignId('company_occupation_id')->constrained();
            $table->foreignId('procedure_id')->constrained();

            $table->primary(['company_occupation_id', 'procedure_id'], 'custom_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_occupation_procedure');
    }
}
