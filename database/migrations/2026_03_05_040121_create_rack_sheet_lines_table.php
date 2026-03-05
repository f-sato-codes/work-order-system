<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rack_sheet_lines', function (Blueprint $table) {
            $table->id();

            $table->foreignId('rack_sheet_id')
                ->constrained('rack_sheets')
                ->comment('どの枠の明細か');

            $table->integer('line_no')->comment('行番号（並び順）');

            $table->string('control_code')
                ->unique()
                ->comment('管理No（物件に1つ・問い合わせキー）');

            $table->string('customer_name')
                ->comment('顧客名');

            $table->string('job_code')
                ->nullable()
                ->comment('物件名（通常は入力しない）');

            $table->integer('planned_qty')
                ->comment('分母（総数）');

            $table->integer('racked_qty')
                ->comment('分子（この枠で吊った数）');

            $table->text('note')
                ->nullable()
                ->comment('備考');

            $table->timestamps();

            $table->unique(['rack_sheet_id', 'line_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rack_sheet_lines');
    }
};