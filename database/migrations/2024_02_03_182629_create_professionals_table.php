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
        Schema::create('professionals', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('name');
            $table->string('persoPhone');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('specialization');
            $table->string('city');
            $table->string('address');
            $table->string('proPhone');
            $table->string('location');
            $table->string('description')->nullable();
            $table->boolean('isAdmin')->default(false);
            $table->boolean('confirmed')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('professionals'); // Changed table name to 'professionals'
    }
};
