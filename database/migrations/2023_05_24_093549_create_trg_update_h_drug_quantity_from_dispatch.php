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
            CREATE OR REPLACE TRIGGER trg_update_table_hospital_drugs_quantity
            AFTER INSERT ON dispatch_items
            FOR EACH ROW
            BEGIN
                UPDATE table_hospital_drugs
                SET quantity = quantity - NEW.quantity
                WHERE drug_id = NEW.drug_id
                AND hospital_id IN (
                    SELECT hospital_id
                    FROM dispatches
                    WHERE id = NEW.dispatch_id
                );
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trg_update_h_drug_quantity_from_dispatch');
    }
};
