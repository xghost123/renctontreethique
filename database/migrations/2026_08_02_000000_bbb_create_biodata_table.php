<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biodata', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_approved')->default(false);
            $table->timestamp('approved_date')->nullable();
            $table->string('running_tab', 50)->nullable();
            $table->string('biodata_code', 50)->nullable();
            $table->integer('biodata_completion')->default(0);
            $table->boolean('pending_approve')->default(false);
            $table->boolean('in_admin_trash')->default(false);
            $table->boolean('in_trash')->default(false);
            $table->boolean('in_edit_request')->default(false);
            $table->boolean('is_blocked')->default(false);
            $table->boolean('special_biodata')->default(false);
            $table->boolean('free_biodata')->default(false);
            $table->integer('daily_free')->default(0);
            $table->string('user_mobile', 50)->nullable();
            $table->string('user_email', 255)->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->date('birth_date')->nullable();
            $table->integer('age')->nullable();
            $table->string('plan', 50)->nullable();
            $table->string('skin_color', 50)->nullable();
            $table->string('height', 50)->nullable();
            $table->string('weight', 50)->nullable();
            $table->string('blood_group', 10)->nullable();
            $table->string('maritial_status', 50)->nullable();
            $table->string('have_children', 50)->nullable();
            $table->string('permanent_country', 100)->nullable();
            $table->string('permanent_division', 100)->nullable();
            $table->string('permanent_district', 100)->nullable();
            $table->string('permanent_upazila', 100)->nullable();
            $table->string('permanent_post_office', 100)->nullable();
            $table->string('permanent_post_code', 20)->nullable();
            $table->string('permanent_union_parishad', 100)->nullable();
            $table->boolean('address_same')->default(true);
            $table->string('temporary_country', 100)->nullable();
            $table->string('temporary_division', 100)->nullable();
            $table->string('temporary_district', 100)->nullable();
            $table->string('temporary_upazila', 100)->nullable();
            $table->string('temporary_post_office', 100)->nullable();
            $table->string('temporary_post_code', 20)->nullable();
            $table->string('temporary_union_parishad', 100)->nullable();
            $table->boolean('address_hide')->default(false);
            // Halal-specific
            $table->string('madhab', 50)->nullable();
            $table->string('prayer_level', 50)->nullable();
            $table->string('islamic_knowledge', 50)->nullable();
            $table->string('children_pref', 50)->nullable();
            $table->string('living_pref', 50)->nullable();
            $table->string('relocation_pref', 50)->nullable();
            $table->string('financial_expectations', 255)->nullable();
            $table->string('roles_outlook', 50)->nullable();
            $table->string('marriage_timeline', 50)->nullable();
            $table->json('languages')->nullable();
            $table->boolean('photo_blurred')->default(true);
            $table->boolean('verified_only_contact')->default(true);
            $table->integer('completeness')->default(0);
            $table->text('bio')->nullable();
            $table->text('looking_for')->nullable();
            $table->timestamps();
            $table->index(['gender', 'is_approved']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biodata');
    }
};
