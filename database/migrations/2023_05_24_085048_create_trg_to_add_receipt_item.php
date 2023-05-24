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
            CREATE OR REPLACE TRIGGER add_receipt_item
            AFTER UPDATE ON table_dispensary_receipt_items
            FOR EACH ROW
            BEGIN
                -- Check if the ID exists in the table
                IF EXISTS (
                    SELECT 1
                    FROM receipt_items
                    WHERE receipt_id = NEW.receipt_id AND drug_id = NEW.drug_id
                ) THEN
                    IF NEW.receipt_id IS NOT NULL AND OLD.receipt_id IS NULL THEN
                        UPDATE receipt_items
                        SET quantity = quantity + NEW.quantity
                        WHERE receipt_id = NEW.receipt_id AND drug_id = OLD.drug_id;
                    END IF;
                ELSE
                    IF NEW.receipt_id IS NOT NULL THEN
                        IF NOT EXISTS (
                            SELECT 1
                            FROM receipt_items
                            WHERE receipt_id = NEW.receipt_id AND drug_id = NEW.drug_id
                        ) THEN
                            INSERT INTO receipt_items (receipt_id, drug_id, quantity)
                            VALUES (NEW.receipt_id, OLD.drug_id, OLD.quantity);
                        END IF;
                    END IF;
                END IF;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trg_to_add_receipt_item');
    }
};
