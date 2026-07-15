<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('upwork_account_id')->constrained('upwork_accounts')->cascadeOnDelete();
            $table->foreignId('project_manager_id')->constrained('users')->cascadeOnDelete();
            $table->string('job_title');
            $table->date('bid_date');
            $table->integer('connects_used')->default(0);
            $table->decimal('proposal_amount', 10, 2)->nullable();
            $table->decimal('client_budget', 10, 2)->nullable();
            $table->enum('status', ['pending', 'won', 'lost', 'no_response'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'bid_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};
