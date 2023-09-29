<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastFourDigitsToReservationCreditCardPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservation_credit_card_payments', function (Blueprint $table) {
            $table->string('last_four_digits')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservation_credit_card_payments', function (Blueprint $table) {
            $table->dropColumn('last_four_digits');
        });
    }
}
