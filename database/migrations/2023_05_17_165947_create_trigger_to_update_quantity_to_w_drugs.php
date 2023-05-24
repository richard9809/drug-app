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
        CREATE TRIGGER `trigger_to_update_quantity_to_w_drugs`
        AFTER UPDATE on inventories
        for each row
        BEGIN
            if old.drug_id != new.drug_id and old.quantity = new.quantity then
                update `table_warehouse_drugs` SET quantity = quantity - old.quantity where drug_id = old.drug_id;
                BEGIN
                    update `table_warehouse_drugs` SET quantity = quantity + new.quantity where drug_id = new.drug_id;
                END;
            end if;
            if old.drug_id = new.drug_id and old.quantity != new.quantity then
                update `table_warehouse_drugs` SET quantity = quantity - old.quantity where drug_id = old.drug_id;
                BEGIN
                    update `table_warehouse_drugs` SET quantity = quantity + new.quantity where drug_id = old.drug_id;
                END;
            end if;
            if old.drug_id != new.drug_id and old.quantity != new.quantity then
                update `table_warehouse_drugs` set quantity = quantity - old.quantity where drug_id = old.drug_id;
                BEGIN
                    update `table_warehouse_drugs` set quantity = quantity + new.quantity where drug_id = new.drug_id;
                END;
            end if;
        END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trigger_to_update_quantity_to_w_drugs');
    }
};
