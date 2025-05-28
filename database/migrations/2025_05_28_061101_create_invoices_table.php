<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservationId')->constrained('reservations');
            $table->string('invoiceNumber')->unique();
            $table->decimal('amount', 8, 2);
            $table->enum('status', ['paid', 'unpaid'])->default('unpaid');
            $table->date('dueDate');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};