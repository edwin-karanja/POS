<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('upc_ean_isbn')->nullable();
            $table->string('item_name');
            $table->string('packaging')->nullable();
            $table->text('description')->nullable();
            $table->string('avatar')->nullable();
            $table->decimal('cost_price', 9, 2);
            $table->decimal('selling_price', 9, 2);
            $table->integer('quantity')->default(0);
            $table->integer('category_id')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
