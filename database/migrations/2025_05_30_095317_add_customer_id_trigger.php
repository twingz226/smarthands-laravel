<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER generate_customer_id BEFORE INSERT ON customers
            FOR EACH ROW
            BEGIN
                IF NEW.customer_id IS NULL THEN
                    SET NEW.customer_id = CONCAT("CUST-", UPPER(SUBSTRING(UUID(), 1, 6)));
                END IF;
            END;
        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS generate_customer_id');
    }
}; 