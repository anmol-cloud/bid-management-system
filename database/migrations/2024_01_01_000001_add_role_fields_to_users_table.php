<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'sales_manager', 'project_manager'])->default('project_manager')->after('email');
            $table->string('phone')->nullable()->after('role');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('phone');
            $table->foreignId('created_by')->nullable()->after('status')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by');
            $table->dropColumn(['role', 'phone', 'status']);
        });
    }
};
