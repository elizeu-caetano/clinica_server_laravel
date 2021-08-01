<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('type', 40);
            $table->string('street');
            $table->string('number', 40);
            $table->string('details')->nullable();
            $table->char('zip', 9)->nullable();
            $table->string('district');
            $table->string('city');
            $table->char('state', 2);
            $table->string('country', 100)->default('Brasil');
            $table->string('city_ibge', 20)->nullable();
            $table->string('state_ibge', 20)->nullable();
            $table->boolean('deleted')->default(false);
            $table->morphs('addressable');
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
        Schema::dropIfExists('addresses');
    }
}
