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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('path', 500);
            $table->string('name')->nullable();
            $table->unsignedBigInteger('imageable_id')->nullable();
            $table->string('imageable_type', 500)->nullable();
            $table->string('type', 500)->nullable();
            $table->integer('priority')->nullable();
            $table->string('priority_type', 500)->nullable();
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
        Schema::dropIfExists('images');
    }
};
