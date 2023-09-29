<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketStocksPdfTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_stocks_pdf', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ticket_stock_id')->unsigned();
            $table->string('path', 500);
            $table->string('name')->nullable();
            $table->foreign('ticket_stock_id')->references('id')->on('ticket_stocks')->onDelete('cascade');
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
        Schema::dropIfExists('ticket_stocks_pdf');
    }
}
