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

            $table->date('work_date');
            $table->integer('daily_slot_no');
            $table->string('rack_no');
            $table->string('spec_code');

            $table->foreignId('created_by_user_id')->constrained('users');

            $table->timestamp('racking_completed_at')->nullable();

            $table->integer('electrolysis_minutes')->nullable();
            $table->timestamp('electrolysis_recorded_at')->nullable();

            $table->timestamp('final_completed_at')->nullable();

            $table->timestamps();

            $table->unique(['work_date', 'daily_slot_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rack_sheets');
    }
};