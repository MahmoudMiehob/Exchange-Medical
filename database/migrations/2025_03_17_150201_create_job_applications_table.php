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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_offer_id');
            $table->string('full_name');
            $table->string('phone');
            $table->text('skill');
            $table->string('type_of_disability')->nullable();
            $table->string('personal_image')->nullable();
            $table->timestamps();

            $table->foreign('job_offer_id')
                  ->references('id')
                  ->on('jobs_offer')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
