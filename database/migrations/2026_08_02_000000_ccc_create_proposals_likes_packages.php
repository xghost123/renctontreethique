<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_user_id')->constrained('users')->onDelete('cascade');
            $table->string('sender_biodata_code', 50)->nullable();
            $table->foreignId('receiver_user_id')->constrained('users')->onDelete('cascade');
            $table->string('receiver_biodata_code', 50)->nullable();
            $table->boolean('proposal_accepted')->default(false);
            $table->timestamp('proposal_sent_datetime')->nullable();
            $table->timestamp('proposal_accepted_datetime')->nullable();
            $table->boolean('proposal_deleted')->default(false);
            $table->boolean('auto_received')->default(false);
            $table->timestamp('auto_received_datetime')->nullable();
            $table->timestamps();
            $table->index(['sender_user_id', 'receiver_user_id']);
        });

        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('biodata_id')->constrained('biodata')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->decimal('price', 10, 2);
            $table->integer('duration_days')->default(30);
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->nullable()->constrained();
            $table->enum('provider', ['stripe', 'paypal', 'paddle'])->default('stripe');
            $table->string('plan_type', 50)->default('monthly');
            $table->string('status', 50)->default('active');
            $table->timestamp('current_period_end')->nullable();
            $table->boolean('cancel_at_period_end')->default(false);
            $table->timestamp('canceled_at')->nullable();
            $table->text('unsubscription_reason')->nullable();
            $table->timestamps();
            $table->index('user_id');
        });

        Schema::create('black_listed_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('reason')->nullable();
            $table->timestamps();
        });

        Schema::create('screening_questions', function (Blueprint $table) {
            $table->id();
            $table->enum('gender', ['male', 'female', 'both'])->default('both');
            $table->text('question');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('screening_questions');
        Schema::dropIfExists('black_listed_users');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('likes');
        Schema::dropIfExists('proposals');
    }
};
