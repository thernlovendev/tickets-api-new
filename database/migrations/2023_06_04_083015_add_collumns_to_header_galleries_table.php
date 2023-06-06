<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCollumnsToHeaderGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('header_galleries', function (Blueprint $table) {
            $table->string('title')->after('id');
            $table->string('first_phrase')->after('title');
            $table->string('second_phrase')->after('first_phrase');
            $table->integer('is_show')->default(0)->after('second_phrase');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('header_galleries', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('first_phrase');
            $table->dropColumn('second_phrase');
            $table->dropColumn('is_show');
            $table->dropSoftDeletes();
        });
    }
}
