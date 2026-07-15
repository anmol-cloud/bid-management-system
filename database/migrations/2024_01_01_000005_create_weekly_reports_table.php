<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weekly_reports', function (Blueprint $table) {
            $table->id();
            $table->date('week_start');
            $table->date('week_end');
            $table->integer('total_bids')->default(0);
            $table->integer('won_bids')->default(0);
            $table->integer('lost_bids')->default(0);
            $table->decimal('success_rate', 5, 2)->default(0);
            $table->string('file_path')->nullable();
            $table->boolean('sent_to_admin')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_reports');
    }
};
