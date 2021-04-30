<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableInventoryGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_groups', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->integer('inventory_group_id')->default(0)->nullable();
            $table->string('product_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_groups');

        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn('inventory_group_id');
            $table->dropColumn('product_description');
        });
    }
}
