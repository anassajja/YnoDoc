<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('professional_id');
            $table->unsignedBigInteger('patient_id');
            $table->dateTime('date');
            $table->string('location');
            $table->string('city');
            $table->string('status')->default('pending');
            $table->time('appointment_time');
            $table->timestamps();

            $table->foreign('professional_id')->references('id')->on('professionals')->onDelete('cascade');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');

            // Include professional_id in the unique constraint
            $table->unique(['patient_id', 'date', 'professional_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
