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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

            // Guest student info (for users who enroll without account)
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_phone')->nullable();

            // Payment info
            $table->string('order_reference')->unique();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['chip', 'manual'])->default('chip');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'cancelled', 'refunded'])->default('pending');
            $table->string('chip_purchase_id')->nullable();
            $table->text('payment_proof')->nullable(); // For manual payment

            // Enrollment status
            $table->enum('status', ['pending', 'active', 'expired', 'cancelled'])->default('pending');
            $table->timestamp('enrolled_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            // Transaction metadata
            $table->json('payment_data')->nullable(); // Store webhook data
            $table->timestamps();

            $table->index(['course_id', 'user_id']);
            $table->index(['guest_email']);
            $table->index(['order_reference']);
            $table->index(['payment_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
