<?php

use Illuminate\Database\Migrations\Migration;

class AlterBooksRemoveDashesFromIsbn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::raw("UPDATE books SET isbn = REPLACE(isbn, '-', '')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
    }
}
