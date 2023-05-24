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
        Schema::create('table_dispensary_drugs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dispensary_id')->constrained('dispensaries')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('drug_id')->constrained('drugs')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_dispensary_drugs');
    }
};
