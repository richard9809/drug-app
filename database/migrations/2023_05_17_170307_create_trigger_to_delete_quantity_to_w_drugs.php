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
        CREATE TRIGGER `trigger_to_delete_quantity_to_w_drugs`
        AFTER DELETE on inventories
        for each row
        BEGIN
            UPDATE `table_warehouse_drugs` SET `quantity` = `quantity` - OLD.quantity WHERE `drug_id` = OLD.drug_id;
        END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trigger_to_delete_quantity_to_w_drugs');
    }
};
