<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_seats', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->unsigned();
            $table->string('product_code', 255);
            $table->string('product_date',255);
            $table->string('product_time',10);
            $table->text('description');
            $table->string('price',255);
            $table->string('regular_price',255);
            $table->string('currency',10);
            $table->string('bestseats',10);
            $table->string('availability',100);
            $table->string('ticket_print_date',255);
            $table->string('base_price',255);
            $table->string('facility_fee',10);
            $table->string('supplier_fee',10);
            $table->string('service_charge',10);
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
        Schema::dropIfExists('product_seats');
    }
}
