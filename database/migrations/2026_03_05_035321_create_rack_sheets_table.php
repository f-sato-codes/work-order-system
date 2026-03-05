<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rack_sheets', function (Blueprint $table) {
            $table->id();

            $table->date('work_date')->comment('作業日');
            $table->integer('daily_slot_no')->comment('当日の生産番号（手入力）');
            $table->string('rack_no')->comment('物理枠番号（手入力）');
            $table->string('spec_code')->comment('色・仕様コード');

            $table->foreignId('created_by_user_id')
                ->constrained('users')
                ->comment('指示書作成者（ラッキング持ち場）');

            $table->time('racking_completed_at')
                ->nullable()
                ->comment('ラッキング完了時刻');

            $table->time('electrolysis_completed_at')
                ->nullable()
                ->comment('電解完了時刻');

            $table->time('finishing_arrived_at')
                ->nullable()
                ->comment('仕上げ場到着＝完了時刻');

            $table->foreignId('completed_by_user_id')
                ->nullable()
                ->constrained('users')
                ->comment('完了ボタン押下ユーザー（梱包・仕上げ持ち場）');

            $table->text('note')
                ->nullable()
                ->comment('備考');

            $table->timestamps();

            $table->unique(['work_date', 'daily_slot_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rack_sheets');
    }
};