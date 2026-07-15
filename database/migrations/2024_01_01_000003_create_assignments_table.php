<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('upwork_account_id')->constrained('upwork_accounts')->cascadeOnDelete();
            $table->foreignId('sales_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('project_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_by')->constrained('users')->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamps();
        });

        Schema::create('assignment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('upwork_account_id')->constrained('upwork_accounts')->cascadeOnDelete();
            $table->foreignId('from_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('changed_by')->constrained('users')->cascadeOnDelete();
            $table->string('role_type');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignment_logs');
        Schema::dropIfExists('assignments');
    }
};
