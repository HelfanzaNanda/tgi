<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableStockOpnameItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_opname_items', function (Blueprint $table) {
            $table->id();
            $table->integer('stock_opname_id');
            $table->integer('inventory_id')->default(0);
            $table->integer('rack_id')->default(0);
            $table->integer('column_id')->default(0);
            $table->decimal('stock_on_book', 50, 4)->nullable();
            $table->decimal('stock_on_physic', 50, 4)->nullable();
            $table->string('note')->nullable();
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
        Schema::dropIfExists('stock_opname_items');
    }
}
