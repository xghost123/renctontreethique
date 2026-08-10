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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('owner_id')->unsigned();
            $table->string('owner_name')->nullable();
            $table->bigInteger('dest_id')->unsigned();
            $table->string('dest_name')->nullable();
            $table->text('last_message')->nullable(); // Cached last message
            $table->enum('status', ['active', 'closed', 'archived'])->default('active');
            $table->bigInteger('closed_by')->unsigned()->nullable();
            $table->text('close_reason')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->boolean('is_moderation_opened')->default(false); // Flag for moderation
            $table->bigInteger('mosque_id')->unsigned()->nullable(); // For mosque-related conversations
            $table->timestamps();

            // Foreign keys
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('dest_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('closed_by')->references('id')->on('users')->onDelete('set null');

            // Indexes for faster queries
            $table->index('owner_id');
            $table->index('dest_id');
            $table->index('status');
            $table->index('updated_at');
            $table->index(['owner_id', 'dest_id']); // For finding existing conversation
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
