<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIgnoreInUpcomingToVolumes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('volumes', function (Blueprint $table): void {
            $table->boolean('ignore_in_upcoming')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('volumes', function (Blueprint $table): void {
            $table->removeColumn('ignore_in_upcoming');
        });
    }
}
