<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeightLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weight_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade'); // 修正: users と紐づけ
            $table->date('date');
            $table->decimal('weight', 4, 1); // 体重　 4桁（小数点前3桁＋小数点後1桁）
            $table->integer('calories')->nullable(); // 食事量
            $table->time('exercise_time')->nullable(); // 運動時間 メール形式からtime型へ修正
            $table->text('exercise_content')->nullable();
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
        Schema::dropIfExists('weight_logs');
    }
}
