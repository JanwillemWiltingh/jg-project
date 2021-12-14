<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('clocker')->truncate();
        Schema::enableForeignKeyConstraints();

        for ($i = 1; $i <= Carbon::now()->daysInMonth; $i++) {
            if(Carbon::parse('2021-12-'.$i)->dayOfWeek != 0 and Carbon::parse('2021-12-'.$i)->dayOfWeek != 6) {
                DB::table('clocker')->insert([
                    'comment' => 'Seeded Comment '.$i,
                    'user_id' => 2,
                    'start_time' => rand(8, 12).':00:00',
                    'end_time' => rand(13, 18).':00:00',
                    'date' => '2021-12-'.$i
                ]);
            }
        }

        for ($i = 1; $i <= Carbon::now()->daysInMonth; $i++) {
            if(Carbon::parse('2021-12-'.$i)->dayOfWeek != 0 and Carbon::parse('2021-12-'.$i)->dayOfWeek != 6) {
                DB::table('clocker')->insert([
                    'comment' => 'Seeded Comment '.$i,
                    'user_id' => 1,
                    'start_time' => rand(8, 12).':00:00',
                    'end_time' => rand(13, 18).':00:00',
                    'date' => '2021-12-'.$i
                ]);
            }
        }
    }
}
