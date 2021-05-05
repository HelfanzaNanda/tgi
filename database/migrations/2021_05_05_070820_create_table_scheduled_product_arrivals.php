<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableScheduledProductArrivals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduled_product_arrivals', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->nullable();
            $table->integer('inventory_id');
            $table->decimal('quantity', 50, 5)->nullable();
            $table->string('customer_order_number')->nullable();
            $table->date('dispatch_date')->nullable();
            $table->date('estimated_time_of_arrival')->nullable();
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
        Schema::dropIfExists('scheduled_product_arrivals');
    }
}
