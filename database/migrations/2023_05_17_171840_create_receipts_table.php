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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // user_id
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete(); // hospital_id       
            $table->string('receipt_number')->unique(); // receipt_number
            $table->date('receipt_date'); // receipt_date
            $table->boolean('is_approved')->default(false); // is_approved
            $table->boolean('is_verified')->default(false); // is_verified
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
