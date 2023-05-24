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
            CREATE TRIGGER `trigger_to_insert_insert_drugs_w_drugs` 
            AFTER INSERT ON `drugs` 
            FOR EACH ROW
            BEGIN
                INSERT INTO `table_warehouse_drugs` (`drug_id`, `quantity`, `created_at`, `updated_at`) VALUES (NEW.id, 0, NOW(), NOW());
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trigger_to_insert_insert_drugs_w_drugs');
    }
};
