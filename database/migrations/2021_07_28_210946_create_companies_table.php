<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('name');
            $table->string('fantasy_name')->nullable();
            $table->string('cpf_cnpj', 40);
            $table->string('state_registration', 100)->nullable();
            $table->enum('type_person', ['F', 'J'])->nullable()->default('J');
            $table->boolean('active')->default(true);
            $table->boolean('deleted')->default(false);
            $table->char('closing_day', 2)->default(0);
            $table->char('pay_day', 2)->default(10);
            $table->string('logo')->nullable();
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
        Schema::dropIfExists('companies');
    }
}
