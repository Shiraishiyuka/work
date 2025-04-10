<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreakTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('break_times', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('attendance_id')->nullable();
    $table->unsignedBigInteger('adjust_id')->nullable();
    $table->time('start_time');
    $table->time('end_time')->nullable();
    $table->timestamps();
    $table->foreign('attendance_id')->references('id')->on('attendances')->onDelete('cascade');
    $table->foreign('adjust_id')->references('id')->on('adjusts')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('break_times');
    }
}
