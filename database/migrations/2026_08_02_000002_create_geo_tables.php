<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('region_id')->nullable();
            $table->string('country_id')->nullable();
            $table->string('country_phone_code')->nullable();
            $table->string('country_name');
            $table->string('country_bn_name')->nullable();
            $table->string('country_short_name')->nullable();
            $table->string('country_population')->nullable();
            $table->timestamps();
        });

        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->nullable();
            $table->string('division_id')->nullable();
            $table->string('division_name');
            $table->string('division_bn_name')->nullable();
            $table->string('division_lat')->nullable();
            $table->string('division_long')->nullable();
            $table->string('division_population')->nullable();
            $table->timestamps();
        });

        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->nullable();
            $table->string('district_id')->nullable();
            $table->string('district_name');
            $table->string('district_bn_name')->nullable();
            $table->string('district_lat')->nullable();
            $table->string('district_long')->nullable();
            $table->string('district_population')->nullable();
            $table->timestamps();
        });

        Schema::create('upazilas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('district_id')->nullable();
            $table->string('upazila_id')->nullable();
            $table->string('upazila_name');
            $table->string('upazila_bn_name')->nullable();
            $table->string('upazila_population')->nullable();
            $table->timestamps();
        });

        Schema::create('postcodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->nullable();
            $table->foreignId('district_id')->nullable();
            $table->string('upazila_name')->nullable();
            $table->string('post_office_name')->nullable();
            $table->string('post_code')->nullable();
            $table->timestamps();
        });

        Schema::create('union_parishads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->nullable();
            $table->foreignId('division_id')->nullable();
            $table->foreignId('district_id')->nullable();
            $table->string('upazila')->nullable();
            $table->string('union_parishad')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('union_parishads');
        Schema::dropIfExists('postcodes');
        Schema::dropIfExists('upazilas');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('divisions');
        Schema::dropIfExists('countries');
    }
};
