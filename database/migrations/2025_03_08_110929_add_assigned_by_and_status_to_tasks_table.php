<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('assigned_by')->nullable()->after('assigned_to')->constrained('users')->onDelete('set null');
            $table->enum('status', ['pending', 'in_progress', 'completed'])->after('assigned_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['assigned_by']);
            $table->dropColumn(['assigned_by', 'status']);
        });
    }
};
