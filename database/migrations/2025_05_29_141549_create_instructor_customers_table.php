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
        Schema::create('instructor_customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructorId')->constrained('instructors')->onDelete('cascade');
            $table->foreignId('customerId')->constrained('customers')->onDelete('cascade');
            
            $table->unique(['instructorId', 'customerId']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructor_customers');
    }
};
