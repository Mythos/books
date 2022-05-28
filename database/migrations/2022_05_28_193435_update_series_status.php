<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $paused = DB::table('series')->where('status', '=', '3')->select('id')->get()->pluck('id');
        $canceled = DB::table('series')->where('status', '=', '4')->select('id')->get()->pluck('id');

        DB::table('series')->whereIn('id', $paused)->update(['status' => '4']);
        DB::table('series')->whereIn('id', $canceled)->update(['status' => '3']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        $paused = DB::table('series')->where('status', '=', '4')->select('id')->get()->pluck('id');
        $canceled = DB::table('series')->where('status', '=', '3')->select('id')->get()->pluck('id');

        DB::table('series')->whereIn('id', $paused)->update(['status' => '3']);
        DB::table('series')->whereIn('id', $canceled)->update(['status' => '4']);
    }
};
