<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('series', function (Blueprint $table): void {
            $table->unsignedTinyInteger('source_status')->nullable();
            $table->string('source_name')->nullable();
            $table->string('source_name_romaji')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('series', function (Blueprint $table): void {
            $table->dropColumn('source_status');
            $table->dropColumn('source_name');
            $table->dropColumn('source_name_romaji');
        });
    }
};
