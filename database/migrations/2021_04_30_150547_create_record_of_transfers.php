<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordOfTransfers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('record_of_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->string('model');
            $table->string('model_id');
            $table->string('type');
            $table->date('date');
            $table->text('description');
            $table->integer('submitted_by');
            $table->integer('received_by');
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
        Schema::dropIfExists('record_of_transfers');
    }
}
