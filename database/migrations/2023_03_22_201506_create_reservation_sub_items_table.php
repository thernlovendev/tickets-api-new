<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationSubItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation_sub_items', function (Blueprint $table) {
            $table->id();
            $table->datetime('rq_schedule_datetime')->nullable();
            $table->double('addition',15,2)->nullable();
            $table->bigInteger('reservation_item_id')->unsigned();
            $table->string('ticket_sent_status')->nullable();
            $table->datetime('ticket_sent_date')->nullable();
            $table->string('refund_status')->nullable();
            $table->datetime('refund_sent_date')->nullable();
            $table->bigInteger('ticket_id')->unsigned();
            
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('reservation_item_id')->references('id')->on('reservation_items')->onDelete('cascade');
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
        Schema::dropIfExists('reservation_sub_items');
    }
}
