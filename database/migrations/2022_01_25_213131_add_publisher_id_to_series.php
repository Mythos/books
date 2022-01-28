<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPublisherIdToSeries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('series', function (Blueprint $table): void {
            $table->foreignId('publisher_id')->nullable()->constrained();
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
            $table->dropForeign(['publisher_id']);
            $table->dropColumn('publisher_id');
        });
    }
}
