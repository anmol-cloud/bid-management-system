<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('upwork_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('upwork_id')->unique();
            $table->string('account_name');
            $table->string('email')->nullable();
            $table->string('profile_url')->nullable();
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->integer('connects_available')->default(0);
            $table->enum('status', ['active', 'suspended', 'inactive'])->default('active');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upwork_accounts');
    }
};
