<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockCorrectionBalanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_correction_balance', function (Blueprint $table) {
            $table->id();
            $table->date('register_date');
            $table->integer('stock_in');
            $table->integer('stock_out');
            $table->string('type');
            $table->string('range_age_type');
            $table->string('created_by');
            $table->bigInteger('ticket_id')->unsigned()->nullable();
            
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
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
        Schema::dropIfExists('stock_correction_balance');
    }
}
