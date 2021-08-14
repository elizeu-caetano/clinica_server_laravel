<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountTableProceduresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_table_procedure', function (Blueprint $table) {
            $table->foreignId('discount_table_id')->constrained();
            $table->foreignId('procedure_id')->constrained();
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('is_percentage')->default(false);

            $table->primary(['discount_table_id', 'procedure_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discount_table_procedure');
    }
}
