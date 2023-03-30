<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->date('departure_date');
            $table->date('order_date');
            $table->string('order_number');
            $table->string('customer_name_en');
            $table->string('customer_name_kr');
            $table->string('phone');
            $table->string('email');
            $table->double('discount_amount',15,2)->default(0);
            $table->double('subtotal',15,2)->default(0);
            $table->double('total',15,2)->default(0);
            $table->text('memo')->nullable();
            $table->text('history')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('ticket_sent_status')->nullable();
            $table->string('status')->nullable();
            $table->string('created_by');
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
        Schema::dropIfExists('reservations');
    }
}
