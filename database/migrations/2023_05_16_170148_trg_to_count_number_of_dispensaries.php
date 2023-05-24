<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER count_number_of_dispensaries
            AFTER INSERT ON dispensaries
            FOR EACH ROW
            UPDATE hospitals
            SET number_of_dispensaries = number_of_dispensaries + 1
            WHERE id = NEW.hospital_id;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('count_number_of_dispensaries');
    }
};
