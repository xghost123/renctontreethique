<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // ProposalCreated, MessageReceived, ProfileApproved, ProposalResponse, ProfileViewed
            $table->string('title');
            $table->text('message');
            $table->string('icon')->nullable(); // bell, envelope, check, heart, eye
            $table->string('color')->nullable(); // success, info, warning, danger
            $table->json('data')->nullable(); // Store related IDs and context
            $table->boolean('read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'read', 'created_at']);
            $table->index(['user_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
