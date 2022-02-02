<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubscriptionActiveMangapassionIdToSeries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('series', function (Blueprint $table): void {
            $table->boolean('subscription_active')->default(false);
            $table->integer('mangapassion_id')->nullable();
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
            $table->dropColumn('subscription_active');
            $table->dropColumn('mangapassion_id');
        });
    }
}
