<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ユーザーID（外部キー）
            $table->date('date');                  // 勤務日
            $table->time('start_time')->nullable(); // 出勤時間
            $table->time('end_time')->nullable();   // 退勤時間
            $table->integer('break_minutes')->default(0); // 休憩の合計時間（分単位）
            $table->integer('work_minutes')->default(0);  // 勤務の合計時間（分単位）
            $table->timestamps();

            // 外部キー制約
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
        Schema::dropIfExists('attendances');
    }
}
