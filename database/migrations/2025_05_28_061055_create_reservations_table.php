<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userId')->constrained('users');
            $table->foreignId('packageId')->constrained('packages');
            $table->foreignId('locationId')->constrained('locations');
            $table->string('duoPartnerName')->nullable();
            $table->string('duoPartnerEmail')->nullable();
            $table->string('duoPartnerAddress')->nullable();
            $table->string('duoPartnerCity')->nullable();
            $table->string('duoPartnerPhone')->nullable();
            $table->date('reservationDate');
            $table->time('reservationTime');
            $table->text('cancellationReason')->nullable();
            $table->enum('cancellationStatus', ['pending', 'approved', 'rejected'])->nullable();
            $table->date('originalDate')->nullable();
            $table->time('originalTime')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};