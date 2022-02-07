<?php

use Illuminate\Database\Migrations\Migration;

class UpdateVolumesChangeDeliveredStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::statement('UPDATE volumes SET status = 3 WHERE status = 2');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::statement('UPDATE volumes SET status = 2 WHERE status = 3');
    }
}
