<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('biodata_id')->unsigned();
            $table->string('path'); // storage path
            $table->string('original_filename');
            $table->bigInteger('size'); // in bytes
            $table->string('mime_type')->default('image/jpeg'); // image type
            $table->boolean('approved')->default(false); // admin approval
            $table->bigInteger('approved_by')->unsigned()->nullable(); // which admin approved
            $table->timestamp('approved_at')->nullable(); // when approved
            $table->integer('display_order')->default(0); // for ordering in gallery
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('biodata_id')->references('id')->on('biodatas')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');

            // Indexes for faster queries
            $table->index('user_id');
            $table->index('biodata_id');
            $table->index('approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
