<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('biodata', function (Blueprint $table) {
            // Zawajuna-style fields (personal info)
            if (!Schema::hasColumn('biodata', 'identifier')) {
                $table->string('identifier', 50)->unique()->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('biodata', 'kounia')) {
                $table->string('kounia', 100)->nullable()->after('identifier');
            }
            if (!Schema::hasColumn('biodata', 'whatsapp')) {
                $table->string('whatsapp', 50)->nullable()->after('user_mobile');
            }
            if (!Schema::hasColumn('biodata', 'nationality')) {
                $table->string('nationality', 100)->nullable()->after('whatsapp');
            }
            if (!Schema::hasColumn('biodata', 'city')) {
                $table->string('city', 100)->nullable()->after('nationality');
            }
            if (!Schema::hasColumn('biodata', 'origine')) {
                $table->string('origine', 100)->nullable()->after('city');
            }
            if (!Schema::hasColumn('biodata', 'spoken_langage')) {
                $table->string('spoken_langage', 50)->nullable()->after('origine');
            }
            
            // Family fields
            if (!Schema::hasColumn('biodata', 'polygamy')) {
                $table->string('polygamy', 50)->nullable()->after('have_children');
            }
            if (!Schema::hasColumn('biodata', 'boys')) {
                $table->integer('boys')->default(0)->after('polygamy');
            }
            if (!Schema::hasColumn('biodata', 'girls')) {
                $table->integer('girls')->default(0)->after('boys');
            }
            if (!Schema::hasColumn('biodata', 'dependentchildren')) {
                $table->string('dependentchildren', 50)->nullable()->after('girls');
            }
            if (!Schema::hasColumn('biodata', 'children_details')) {
                $table->text('children_details')->nullable()->after('dependentchildren');
            }
            if (!Schema::hasColumn('biodata', 'has_tutor')) {
                $table->boolean('has_tutor')->nullable()->after('children_details');
            }
            if (!Schema::hasColumn('biodata', 'tutorname')) {
                $table->string('tutorname', 100)->nullable()->after('has_tutor');
            }
            if (!Schema::hasColumn('biodata', 'tutorphone')) {
                $table->string('tutorphone', 50)->nullable()->after('tutorname');
            }
            if (!Schema::hasColumn('biodata', 'tutoraffiliation')) {
                $table->string('tutoraffiliation', 100)->nullable()->after('tutorphone');
            }
            
            // Appearance fields
            if (!Schema::hasColumn('biodata', 'job')) {
                $table->string('job', 100)->nullable()->after('job_title');
            }
            if (!Schema::hasColumn('biodata', 'tall')) {
                $table->string('tall', 50)->nullable()->after('height');
            }
            if (!Schema::hasColumn('biodata', 'body_type')) {
                $table->string('body_type', 50)->nullable()->after('weight');
            }
            
            // Religion fields
            if (!Schema::hasColumn('biodata', 'salafy')) {
                $table->string('salafy', 100)->nullable()->after('islamic_knowledge');
            }
            if (!Schema::hasColumn('biodata', 'hijra')) {
                $table->string('hijra', 100)->nullable()->after('salafy');
            }
            if (!Schema::hasColumn('biodata', 'practice_religion_years')) {
                $table->integer('practice_religion_years')->default(0)->after('hijra');
            }
            if (!Schema::hasColumn('biodata', 'dress_code_text')) {
                $table->text('dress_code_text')->nullable()->after('practice_religion_years');
            }
            if (!Schema::hasColumn('biodata', 'scholars')) {
                $table->text('scholars')->nullable()->after('dress_code_text');
            }
            
            // Health & Bio
            if (!Schema::hasColumn('biodata', 'health')) {
                $table->text('health')->nullable()->after('scholars');
            }
            if (!Schema::hasColumn('biodata', 'occult')) {
                $table->text('occult')->nullable()->after('health');
            }
            if (!Schema::hasColumn('biodata', 'profilstatus')) {
                $table->string('profilstatus', 50)->default('new')->after('occult');
            }
        });
    }

    public function down(): void
    {
        Schema::table('biodata', function (Blueprint $table) {
            $dropColumns = [
                'identifier', 'kounia', 'whatsapp', 'nationality', 'city', 'origine', 'spoken_langage',
                'polygamy', 'boys', 'girls', 'dependentchildren', 'children_details', 
                'has_tutor', 'tutorname', 'tutorphone', 'tutoraffiliation',
                'job', 'tall', 'body_type',
                'salafy', 'hijra', 'practice_religion_years', 'dress_code_text', 'scholars',
                'health', 'occult', 'profilstatus'
            ];
            
            foreach ($dropColumns as $col) {
                if (Schema::hasColumn('biodata', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
