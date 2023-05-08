<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNavigationSubMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigation_sub_menus', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->bigInteger('navigation_menu_id')->unsigned()->nullable();
            $table->bigInteger('template_id')->unsigned()->nullable();
            
            $table->foreign('navigation_menu_id')->references('id')->on('navigation_menus')->onDelete('cascade');
            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');
            
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
        Schema::dropIfExists('navigation_sub_menus');
    }
}
