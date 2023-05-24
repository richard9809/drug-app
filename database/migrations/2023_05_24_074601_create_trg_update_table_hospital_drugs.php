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
            CREATE TRIGGER trg_update_table_hospital_drugs 
            AFTER UPDATE ON receipts
            FOR EACH ROW
            BEGIN
                IF NEW.is_approved = 1 THEN
                    -- Update existing records
                    UPDATE table_hospital_drugs
                    SET quantity = quantity + (
                        SELECT quantity
                        FROM receipt_items
                        WHERE receipt_id = NEW.id
                          AND table_hospital_drugs.hospital_id = NEW.hospital_id
                          AND table_hospital_drugs.drug_id = receipt_items.drug_id
                    )
                    WHERE EXISTS (
                        SELECT 1
                        FROM receipt_items
                        WHERE receipt_id = NEW.id
                          AND table_hospital_drugs.hospital_id = NEW.hospital_id
                          AND table_hospital_drugs.drug_id = receipt_items.drug_id
                    );
    
                    -- Insert new records
                    INSERT INTO table_hospital_drugs (hospital_id, drug_id, quantity)
                    SELECT NEW.hospital_id, drug_id, quantity
                    FROM receipt_items
                    WHERE receipt_id = NEW.id
                      AND NOT EXISTS (
                          SELECT 1
                          FROM table_hospital_drugs
                          WHERE hospital_id = NEW.hospital_id
                            AND drug_id = receipt_items.drug_id
                      );
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trg_update_table_hospital_drugs');
    }
};
