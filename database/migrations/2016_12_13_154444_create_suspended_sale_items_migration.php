<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuspendedSaleItemsMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suspended_sale_items', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('suspended_sales_id');
            $table->integer('item_id');
            $table->string('cost_price');
            $table->string('selling_price');
            $table->integer('quantity');
            $table->string('item_total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
