<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSubcategoryIdNullableToReservationItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservation_items', function (Blueprint $table) {
            $table->bigInteger('subcategory_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservation_items', function (Blueprint $table) {
            $table->bigInteger('subcategory_id')->unsigned()->change();
        });
    }
}
