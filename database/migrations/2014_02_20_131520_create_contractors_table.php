<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contractors', function (Blueprint $table) {
             $table->id();
            $table->uuid('uuid');
            $table->string('name');
            $table->string('fantasy_name')->nullable();
            $table->string('cpf_cnpj', 40);
            $table->enum('type_person', ['F', 'J'])->nullable()->default('J');
            $table->boolean('active')->default(true);
            $table->boolean('deleted')->default(false);
            $table->string('logo')->nullable();
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
        Schema::dropIfExists('contractors');
    }
}
