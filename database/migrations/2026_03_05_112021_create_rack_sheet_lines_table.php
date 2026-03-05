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
                ->cascadeOnDelete();

            $table->integer('line_no');

            $table->string('control_code');
            $table->string('customer_name');
            $table->string('job_code')->nullable();

            $table->integer('planned_qty');
            $table->integer('racked_qty');

            $table->timestamps();

            $table->unique(['rack_sheet_id','line_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rack_sheet_lines');
    }
};