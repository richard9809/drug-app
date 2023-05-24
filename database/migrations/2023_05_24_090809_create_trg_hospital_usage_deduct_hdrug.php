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
            create or replace trigger trg_hospital_usage_deduct_hdrug
            after insert on hospital_usages
            for each row
            begin
                update hospital_usages set quantity =  quantity - new.quantity where drug_id = new.drug_id and hospital_id = new.hospital_id;
            end;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trg_hospital_usage_deduct_hdrug');
    }
};
