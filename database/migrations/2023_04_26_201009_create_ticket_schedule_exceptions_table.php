<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketScheduleExceptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_schedule_exceptions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('max_people');
            $table->time('time');
            $table->boolean('show_on_calendar');
            $table->string('day');
            $table->bigInteger('ticket_schedule_id')->unsigned();

            $table->foreign('ticket_schedule_id')->references('id')->on('ticket_schedules')->onDelete('cascade');
            
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
        Schema::dropIfExists('ticket_schedule_exceptions');
    }
}
