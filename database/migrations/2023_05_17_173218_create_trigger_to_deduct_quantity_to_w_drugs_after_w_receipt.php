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
            CREATE OR REPLACE TRIGGER update_table_warehouse_drugs_quantity
            AFTER UPDATE ON receipts
            FOR EACH ROW
            BEGIN
                IF NEW.is_approved = 1 THEN
                    UPDATE table_warehouse_drugs
                    SET quantity = quantity - (
                        SELECT SUM(ri.quantity)
                        FROM receipt_items ri
                        WHERE ri.receipt_id = NEW.id
                        AND ri.drug_id = table_warehouse_drugs.drug_id
                    )
                    WHERE drug_id IN (
                        SELECT ri.drug_id
                        FROM receipt_items ri
                        WHERE ri.receipt_id = NEW.id
                    );
                END IF;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('update_table_warehouse_drugs_quantity');
    }
};
