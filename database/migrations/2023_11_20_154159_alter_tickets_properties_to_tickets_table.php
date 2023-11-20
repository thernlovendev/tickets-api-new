<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTicketsPropertiesToTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('status')->nullable()->change();
            $table->string('additional_price_type')->nullable()->change();

        });
        Schema::table('ticket_contents', function (Blueprint $table) {
            $table->text('content')->nullable()->change();

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
            $table->string('status')->change();
            $table->string('additional_price_type')->change();

        });
        Schema::table('ticket_contents', function (Blueprint $table) {
            $table->text('content')->change();

        });
    }
}
