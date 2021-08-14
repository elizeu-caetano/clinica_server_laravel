<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyDiscountTableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_discount_table', function (Blueprint $table) {
            $table->foreignId('company_id')->constrained();
            $table->foreignId('discount_table_id')->constrained();

            $table->primary(['company_id', 'discount_table_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_discount_table');
    }
}
