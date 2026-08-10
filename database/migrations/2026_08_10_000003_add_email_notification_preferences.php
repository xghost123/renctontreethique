<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notification_preferences', function (Blueprint $table) {
            // Add new email notification columns
            if (!Schema::hasColumn('notification_preferences', 'email_on_registration')) {
                $table->boolean('email_on_registration')->default(true)->after('user_id');
            }
            if (!Schema::hasColumn('notification_preferences', 'email_on_biodata_status')) {
                $table->boolean('email_on_biodata_status')->default(true)->after('email_on_registration');
            }
            if (!Schema::hasColumn('notification_preferences', 'email_on_proposal')) {
                $table->boolean('email_on_proposal')->default(true)->after('email_on_biodata_status');
            }
            if (!Schema::hasColumn('notification_preferences', 'email_on_proposal_response')) {
                $table->boolean('email_on_proposal_response')->default(true)->after('email_on_proposal');
            }
            if (!Schema::hasColumn('notification_preferences', 'email_on_message')) {
                $table->boolean('email_on_message')->default(true)->after('email_on_proposal_response');
            }
            if (!Schema::hasColumn('notification_preferences', 'email_on_like')) {
                $table->boolean('email_on_like')->default(true)->after('email_on_message');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notification_preferences', function (Blueprint $table) {
            $table->dropColumn([
                'email_on_registration',
                'email_on_biodata_status',
                'email_on_proposal',
                'email_on_proposal_response',
                'email_on_message',
                'email_on_like',
            ]);
        });
    }
};
