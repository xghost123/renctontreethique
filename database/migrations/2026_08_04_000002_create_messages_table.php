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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('conversation_id')->unsigned();
            $table->bigInteger('sender_id')->unsigned();
            $table->bigInteger('recipient_id')->unsigned();
            $table->text('body');
            $table->string('language')->default('fr'); // Message language (fr, en, etc)
            $table->enum('status', ['sent', 'delivered', 'read'])->default('sent'); // Message delivery status
            $table->timestamp('read_at')->nullable(); // When recipient read the message
            $table->boolean('is_flagged')->default(false); // User flagged for moderation
            $table->string('flag_reason')->nullable(); // Why flagged
            $table->text('moderation_note')->nullable(); // Admin moderation notes
            $table->timestamp('moderated_at')->nullable(); // When moderated
            $table->bigInteger('moderated_by')->unsigned()->nullable(); // Which admin moderated
            $table->timestamps(); // created_at, updated_at

            // Foreign keys
            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('moderated_by')->references('id')->on('users')->onDelete('set null');

            // Indexes for faster queries
            $table->index('conversation_id');
            $table->index('sender_id');
            $table->index('recipient_id');
            $table->index('status');
            $table->index('read_at');
            $table->index('is_flagged');
            $table->index(['conversation_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
