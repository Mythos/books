<?php

use Illuminate\Database\Migrations\Migration;

class UpdateVolumesFixIsbn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::statement("UPDATE volumes SET isbn = REPLACE(isbn, '-', '') WHERE isbn LIKE '%-%';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        //
    }
}
