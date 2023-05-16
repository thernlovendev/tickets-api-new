<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStockAlertToTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->integer('out_of_stock_alert_adult')->nullable();
            $table->integer('out_of_stock_alert_child')->nullable();

            $table->dropColumn('out_of_stock_alert');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('out_of_stock_alert_adult');
            $table->dropColumn('out_of_stock_alert_child');
            
            $table->string('out_of_stock_alert')->nullable();
        });
    }
}
