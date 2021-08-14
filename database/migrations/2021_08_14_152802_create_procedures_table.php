<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProceduresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procedures', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('name');
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('commission', 10, 2)->default(0);
            $table->decimal('material', 10, 2)->default(0);
            $table->boolean('is_percentage')->default(true);
            $table->boolean('active')->default(true);
            $table->boolean('deleted')->default(false);
            $table->boolean('is_print')->default(true);
            $table->foreignId('procedure_group_id')->constrained();
            $table->foreignId('contractor_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('procedures');
    }
}
