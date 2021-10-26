<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoosterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('rooster')->truncate();
        Schema::enableForeignKeyConstraints();

//        39 - 43
        $arrays = [[36, 38], [39, 42], [43, 49]];
        foreach($arrays as $array) {
            for($i = 1; $i <= 5; $i++) {
                DB::table('rooster')->insert([
                    'start_time' => '08:30:00',
                    'end_time' => '17:00:00',
                    'comment' => 'Seeded Comment',
                    'from_home' => 0,
                    'weekdays' => $i,
                    'user_id' => 2,
                    'start_week' => $array[0],
                    'end_week' => $array[1],
                    'disabled' => 0,
                    'year' => 2021
                ]);
            }
        }
    }
}
