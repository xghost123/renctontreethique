<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('biodata', function (Blueprint $table) {
            // Mosque field (link to mosque system)
            if (!Schema::hasColumn('biodata', 'mosque_name')) {
                $table->string('mosque_name', 100)->nullable()->after('city')->comment('Name of mosque attended');
            }
            
            // Divorce count (if divorced)
            if (!Schema::hasColumn('biodata', 'divorce_count')) {
                $table->integer('divorce_count')->nullable()->after('dependentchildren')->comment('Number of times divorced');
            }
            
            // Relocation acceptance (Yes, No, Nearby only, To discuss)
            if (!Schema::hasColumn('biodata', 'relocation_acceptance')) {
                $table->string('relocation_acceptance', 50)->nullable()->after('permanent_country')->comment('Willing to relocate: Oui, Non, À proximité uniquement, À discuter');
            }
        });
    }

    public function down(): void
    {
        Schema::table('biodata', function (Blueprint $table) {
            if (Schema::hasColumn('biodata', 'mosque_name')) {
                $table->dropColumn('mosque_name');
            }
            if (Schema::hasColumn('biodata', 'divorce_count')) {
                $table->dropColumn('divorce_count');
            }
            if (Schema::hasColumn('biodata', 'relocation_acceptance')) {
                $table->dropColumn('relocation_acceptance');
            }
        });
    }
};
