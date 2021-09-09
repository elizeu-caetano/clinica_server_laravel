<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyOccupationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_occupations', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->foreignId('company_id')->constrained();
            $table->foreignId('occupation_id')->constrained();

            $table->primary(['id', 'company_id', 'occupation_id'], 'custom_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_occupations');
    }
}
