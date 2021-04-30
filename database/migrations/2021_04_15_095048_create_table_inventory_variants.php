<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableInventoryVariants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_variants', function (Blueprint $table) {
            $table->id();
            $table->integer('inventory_id');
            $table->integer('variant_id');
            $table->string('variant_name');
            $table->integer('sub_variant_id');
            $table->string('sub_variant_name');
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
        Schema::dropIfExists('inventory_variants');
    }
}
