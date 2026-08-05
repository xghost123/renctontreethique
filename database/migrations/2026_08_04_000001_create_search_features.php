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
        // Create saved_searches table
        Schema::create('saved_searches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->json('filters'); // Store filter criteria as JSON
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Index for quick lookup
            $table->index(['user_id', 'is_active']);
        });

        // Add indexes to biodata table for search optimization
        Schema::table('biodata', function (Blueprint $table) {
            // Composite indexes for common search patterns
            $table->index(['gender', 'is_approved', 'in_trash']);
            $table->index(['age', 'is_approved']);
            $table->index(['permanent_country', 'is_approved']);
            $table->index(['permanent_division', 'is_approved']);
            $table->index(['prayer_level', 'is_approved']);
            $table->index(['madhab', 'is_approved']);
            $table->index(['maritial_status', 'is_approved']);
            $table->index(['created_at', 'is_approved']);
            $table->index(['updated_at', 'is_approved']);

            // Partial indexes (for SQLite, we can't use partial but this documents intent)
            $table->index(['general_selected', 'is_approved']);
            $table->index(['aliya_selected', 'is_approved']);
            $table->index(['kowmi_selected', 'is_approved']);
            $table->index(['practice_religion_years', 'is_approved']);
            $table->index(['skin_color', 'is_approved']);
            $table->index(['height', 'is_approved']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_searches');

        // Drop indexes from biodata
        Schema::table('biodata', function (Blueprint $table) {
            $table->dropIndex(['gender', 'is_approved', 'in_trash']);
            $table->dropIndex(['age', 'is_approved']);
            $table->dropIndex(['permanent_country', 'is_approved']);
            $table->dropIndex(['permanent_division', 'is_approved']);
            $table->dropIndex(['prayer_level', 'is_approved']);
            $table->dropIndex(['madhab', 'is_approved']);
            $table->dropIndex(['maritial_status', 'is_approved']);
            $table->dropIndex(['created_at', 'is_approved']);
            $table->dropIndex(['updated_at', 'is_approved']);
            $table->dropIndex(['general_selected', 'is_approved']);
            $table->dropIndex(['aliya_selected', 'is_approved']);
            $table->dropIndex(['kowmi_selected', 'is_approved']);
            $table->dropIndex(['practice_religion_years', 'is_approved']);
            $table->dropIndex(['skin_color', 'is_approved']);
            $table->dropIndex(['height', 'is_approved']);
        });
    }
};
