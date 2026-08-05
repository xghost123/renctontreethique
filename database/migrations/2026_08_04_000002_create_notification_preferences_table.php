<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            
            // Email preferences
            $table->boolean('email_proposal_created')->default(true);
            $table->boolean('email_message_received')->default(true);
            $table->boolean('email_profile_approved')->default(true);
            $table->boolean('email_proposal_response')->default(true);
            $table->boolean('email_profile_viewed')->default(false);
            
            // In-app preferences
            $table->boolean('inapp_proposal_created')->default(true);
            $table->boolean('inapp_message_received')->default(true);
            $table->boolean('inapp_profile_approved')->default(true);
            $table->boolean('inapp_proposal_response')->default(true);
            $table->boolean('inapp_profile_viewed')->default(true);
            
            // Frequency settings
            $table->enum('email_frequency', ['immediate', 'daily', 'weekly'])->default('immediate');
            
            $table->timestamps();
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};
