<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('code_number');
            $table->string('type');
            $table->date('expiration_date');
            $table->string('status');
            $table->string('range_age_type');
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
        Schema::dropIfExists('ticket_stocks');
    }
}
