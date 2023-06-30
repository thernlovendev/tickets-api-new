<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCollumnsToNavigationMenusAndNavigationSubmenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('navigation_sub_menus', function (Blueprint $table) {
            $table->foreignId('ticket_id')->nullable()->constrained();
        });
        Schema::table('navigation_menus', function (Blueprint $table) {
            $table->foreignId('ticket_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('navigation_sub_menus', function (Blueprint $table) {
            $table->dropColumn('ticket_id');
        });
        Schema::table('navigation_menus', function (Blueprint $table) {
            $table->dropColumn('ticket_id');
        });
    }
}
