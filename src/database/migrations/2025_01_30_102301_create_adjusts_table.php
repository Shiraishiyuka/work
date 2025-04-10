<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdjustsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjusts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attendance_id'); 
            $table->unsignedBigInteger('user_id');
            $table->date('date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('break_minutes')->default(0);
            $table->integer('work_minutes')->default(0);
            $table->text('remarks')->nullable();
            $table->enum('status', ['pending', 'approved'])->default('pending');
            $table->timestamps();


            $table->foreign('attendance_id')->references('id')->on('attendances')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adjusts', function (Blueprint $table) {
        $table->dropForeign(['attendance_id']);
        $table->dropForeign(['user_id']);
    });

    Schema::dropIfExists('adjusts');

    
    }
}
