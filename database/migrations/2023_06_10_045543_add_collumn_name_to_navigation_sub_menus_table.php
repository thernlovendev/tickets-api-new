<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCollumnNameToNavigationSubMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('navigation_sub_menus', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->string('url')->nullable()->change();
        });
        Schema::table('navigation_menus', function (Blueprint $table) {
            $table->string('static_page')->nullable()->change();
            $table->string('url')->nullable()->change();
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
            $table->dropColumn('name');
        });
    }
}
