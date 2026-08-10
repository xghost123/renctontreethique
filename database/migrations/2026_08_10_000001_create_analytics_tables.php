<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Profile view analytics
        Schema::create('profile_views', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('biodata_id')->index();
            $table->unsignedBigInteger('viewer_biodata_id')->nullable();
            $table->string('viewer_ip')->nullable();
            $table->timestamps();
            
            $table->foreign('biodata_id')->references('id')->on('biodata')->onDelete('cascade');
        });

        // Like analytics
        Schema::create('like_analytics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('biodata_id')->index();
            $table->unsignedBigInteger('liker_biodata_id')->nullable();
            $table->string('like_type')->default('profile'); // profile, mutual
            $table->timestamps();
            
            $table->foreign('biodata_id')->references('id')->on('biodata')->onDelete('cascade');
        });

        // Activity heatmap (hourly aggregation)
        Schema::create('activity_heatmap', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->integer('day_of_week'); // 0-6 (Sunday-Saturday)
            $table->integer('hour'); // 0-23
            $table->integer('activity_count')->default(0);
            $table->timestamps();
            
            $table->unique(['user_id', 'day_of_week', 'hour']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Proposal analytics
        Schema::create('proposal_analytics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sent_by_biodata_id')->index();
            $table->unsignedBigInteger('sent_to_biodata_id')->index();
            $table->string('status')->default('pending'); // pending, accepted, rejected, expired
            $table->integer('response_time_seconds')->nullable(); // in seconds
            $table->timestamps();
            
            $table->foreign('sent_by_biodata_id')->references('id')->on('biodata')->onDelete('cascade');
            $table->foreign('sent_to_biodata_id')->references('id')->on('biodata')->onDelete('cascade');
        });

        // Message analytics
        Schema::create('message_analytics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_biodata_id')->index();
            $table->unsignedBigInteger('receiver_biodata_id')->index();
            $table->integer('conversation_count')->default(0);
            $table->timestamps();
            
            $table->unique(['sender_biodata_id', 'receiver_biodata_id']);
            $table->foreign('sender_biodata_id')->references('id')->on('biodata')->onDelete('cascade');
            $table->foreign('receiver_biodata_id')->references('id')->on('biodata')->onDelete('cascade');
        });

        // Demographics of viewers
        Schema::create('viewer_demographics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('biodata_id')->index();
            $table->unsignedBigInteger('viewer_biodata_id');
            $table->integer('viewer_age')->nullable();
            $table->string('viewer_location')->nullable();
            $table->timestamps();
            
            $table->foreign('biodata_id')->references('id')->on('biodata')->onDelete('cascade');
        });

        // Daily aggregated stats (for performance)
        Schema::create('daily_analytics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->date('date')->index();
            $table->integer('profile_views')->default(0);
            $table->integer('likes_received')->default(0);
            $table->integer('messages_sent')->default(0);
            $table->integer('messages_received')->default(0);
            $table->integer('proposals_sent')->default(0);
            $table->integer('proposals_received')->default(0);
            $table->integer('proposals_accepted')->default(0);
            $table->timestamps();
            
            $table->unique(['user_id', 'date']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Monthly analytics summary
        Schema::create('monthly_analytics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->year('year');
            $table->month('month');
            $table->integer('total_profile_views')->default(0);
            $table->integer('total_likes_received')->default(0);
            $table->integer('total_messages_sent')->default(0);
            $table->integer('total_messages_received')->default(0);
            $table->integer('total_proposals_sent')->default(0);
            $table->integer('total_proposals_received')->default(0);
            $table->integer('total_proposals_accepted')->default(0);
            $table->integer('unique_visitors')->default(0);
            $table->timestamps();
            
            $table->unique(['user_id', 'year', 'month']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_analytics');
        Schema::dropIfExists('daily_analytics');
        Schema::dropIfExists('viewer_demographics');
        Schema::dropIfExists('message_analytics');
        Schema::dropIfExists('proposal_analytics');
        Schema::dropIfExists('activity_heatmap');
        Schema::dropIfExists('like_analytics');
        Schema::dropIfExists('profile_views');
    }
};
