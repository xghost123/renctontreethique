<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Verifications table (nikah docHash system)
        Schema::create('verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['ID', 'FACE', 'PHONE', 'EMAIL', 'EDUCATION', 'PROFESSION']);
            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED'])->default('PENDING');
            $table->foreignId('reviewer_id')->nullable()->references('id')->on('users');
            $table->string('doc_hash')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'type']);
        });

        // Conversations (zawajuna-style chat)
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('active');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dest_id')->constrained('users')->onDelete('cascade');
            $table->string('owner_name')->nullable();
            $table->string('dest_name')->nullable();
            $table->text('last_message')->nullable();
            $table->string('close_reason')->nullable();
            $table->foreignId('closed_by')->nullable()->references('id')->on('users');
            $table->timestamp('closed_at')->nullable();
            $table->boolean('is_moderation_opened')->default(false);
            $table->timestamps();
            $table->index(['owner_id', 'dest_id']);
        });

        // Messages with AI moderation score (zawajuna)
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->text('message');
            $table->string('langage', 10)->default('fr');
            $table->string('status')->default('sent');
            $table->foreignId('owner_id')->nullable()->references('id')->on('users');
            $table->foreignId('dest_id')->nullable()->references('id')->on('users');
            $table->text('rejected_reason')->nullable();
            $table->decimal('confidence_score_by_ia', 5, 2)->nullable();
            $table->timestamps();
            $table->index('conversation_id');
        });

        // Moderator profiles (zawajuna moderation)
        Schema::create('moderator_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('moderator_name')->nullable();
            $table->enum('comment_type', ['note', 'warning', 'ban'])->default('note');
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->index('user_id');
        });

        // Black listed users (moved to ccc migration)
        // Screening questions (moved to ccc migration)
    }

    public function down(): void
    {
        Schema::dropIfExists('moderator_profiles');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversations');
        Schema::dropIfExists('verifications');
    }
};
