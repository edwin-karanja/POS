<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_costs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('item_id')->unsigned();
            $table->decimal('cost', 9, 2);
            $table->decimal('selling_price', 9, 2);
            $table->softDeletes();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_costs');
    }
}
