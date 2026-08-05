<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ============ MOSQUES (the trust anchor) ============
        Schema::create('mosques', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('address')->nullable();
            $table->string('imam_name')->nullable();
            $table->string('imam_phone')->nullable();
            $table->string('imam_email')->nullable();
            $table->enum('status', ['pending', 'active', 'suspended'])->default('pending');
            $table->foreignId('created_by')->nullable()->references('id')->on('users');
            $table->timestamps();
        });

        // ============ MOSQUE MEMBERSHIP (approval flow) ============
        Schema::create('mosque_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mosque_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['member', 'moderator', 'imam'])->default('member');
            // member  = approved member of the mosque
            // moderator = can approve new members
            // imam     = mosque leader (full control)
            $table->enum('status', ['pending', 'approved', 'rejected', 'removed'])->default('pending');
            $table->foreignId('approved_by')->nullable()->references('id')->on('users');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->unique(['mosque_id', 'user_id']);
            $table->index('status');
        });

        // ============ MOSQUE PROPOSALS (men initiate, women accept) ============
        Schema::create('mosque_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mosque_id')->constrained()->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');   // brother
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade'); // sister
            $table->enum('status', ['pending', 'accepted', 'declined', 'withdrawn', 'expired'])->default('pending');
            $table->text('message')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
            $table->unique(['sender_id', 'receiver_id']); // one active proposal per pair
            $table->index(['mosque_id', 'status']);
            $table->index('receiver_id');
        });

        // ============ VISIBILITY RULE (strict isolation) ============
        // A user is ONLY discoverable by approved members of the SAME mosque.
        // The biodata profile gets a mosque_id so searches filter by it.
        Schema::table('biodata', function (Blueprint $table) {
            $table->foreignId('mosque_id')->nullable()->after('user_id')->references('id')->on('mosques');
            $table->boolean('visible_to_mosque')->default(true)->after('mosque_id');
            $table->index('mosque_id');
        });

        // ============ MODERATOR REQUEST QUEUE ============
        Schema::create('mosque_join_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mosque_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('note')->nullable();  // "I pray at this mosque, my imam is X"
            $table->foreignId('reviewed_by')->nullable()->references('id')->on('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->index(['mosque_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mosque_join_requests');
        Schema::table('biodata', function (Blueprint $table) {
            $table->dropForeign(['mosque_id']);
            $table->dropColumn(['mosque_id', 'visible_to_mosque']);
        });
        Schema::dropIfExists('mosque_proposals');
        Schema::dropIfExists('mosque_memberships');
        Schema::dropIfExists('mosques');
    }
};
