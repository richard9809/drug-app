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
            CREATE OR REPLACE TRIGGER trg_update_d_drug_quantity_after_dispatch
            AFTER INSERT ON dispatch_items
            FOR EACH ROW
            BEGIN
                SET @v_dispensary_id = (
                    SELECT dispensary_id
                    FROM dispatches
                    WHERE id = NEW.dispatch_id
                );
                SET @v_row_count = (
                    SELECT COUNT(*)
                    FROM table_dispensary_drugs
                    WHERE drug_id = NEW.drug_id
                    AND dispensary_id = @v_dispensary_id
                );
                IF @v_row_count > 0 THEN
                    UPDATE table_dispensary_drugs
                    SET quantity = quantity + NEW.quantity
                    WHERE drug_id = NEW.drug_id
                    AND dispensary_id = @v_dispensary_id;
                ELSE
                    INSERT INTO table_dispensary_drugs (dispensary_id, drug_id, quantity)
                    VALUES (@v_dispensary_id, NEW.drug_id, NEW.quantity);
                END IF;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trg_update_d_drug_quantity_after_dispatch');
    }
};
