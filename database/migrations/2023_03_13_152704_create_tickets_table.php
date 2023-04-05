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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title_en');
            $table->string('title_kr');
            $table->string('ticket_template');
            $table->string('ticket_type');
            $table->string('status');
            $table->string('out_of_stock_alert');
            $table->string('currency');
            $table->string('product_code');
            $table->string('additional_price_type');
            $table->integer('additional_price_amount')->default(0);
            $table->string('additional_price_image')->nullable();
            $table->boolean('show_in_schedule_page')->default(false);
            $table->string('announcement');
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('city_id')->unsigned();
            
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
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
        Schema::dropIfExists('tickets');
    }
};
