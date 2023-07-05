<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionsSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('options_schedules', function (Blueprint $table) {
            $table->id();
            $table->datetime('datetime');
            $table->integer('order');
            $table->bigInteger('reservation_sub_item_id')->unsigned()->nullable();
            
            $table->foreign('reservation_sub_item_id')->references('id')->on('reservation_sub_items')->onDelete('cascade');
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
        Schema::dropIfExists('options_schedules');
    }
}
