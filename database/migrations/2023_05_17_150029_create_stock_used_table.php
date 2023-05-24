<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockUsedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_used', function (Blueprint $table) {
            $table->id();
            $table->date('date_used')->nullable();
            $table->bigInteger('ticket_stock_id')->unsigned()->nullable();
            $table->bigInteger('reservation_id')->unsigned()->nullable();
            $table->bigInteger('reservation_sub_item_id')->unsigned()->nullable();
            
            $table->foreign('ticket_stock_id')->references('id')->on('ticket_stocks')->onDelete('cascade');
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
            $table->foreign('reservation_sub_item_id')->references('id')->on('reservation_sub_items')->onDelete('cascade');
            $table->softDeletes();            
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
        Schema::dropIfExists('stock_used');
    }
}
