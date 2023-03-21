<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_prices', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('age_limit')->nullable();
            $table->string('window_price')->nullable();
            $table->float('sale_price');
            $table->bigInteger('ticket_id')->unsigned();

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
        Schema::dropIfExists('ticket_prices');
    }
};
