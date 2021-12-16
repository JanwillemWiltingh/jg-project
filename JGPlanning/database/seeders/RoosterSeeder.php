<?php

namespace Database\Seeders;

use App\Models\User;
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
//        $users = User::all();
//        foreach ($users as $user){
//            DB::table('rooster')->insert([
//                'start_time' => '08:45:00',
//                'end_time' => '17:15:00',
//                'comment' => 'Dit is waar je je comment neerzet',
//                'from_home' => 0,
//                'weekdays' => 2,
//                'user_id' => $user['id'],
//                'start_week' => 40,
//                'end_week' => 52,
//                'disabled' => 0,
//                'start_year' => 2021,
//                'end_year' => 2021,
//            ]);
//        }

        $arrays = [[36, 38], [39, 42], [43, 49], [50, 52]];
        foreach($arrays as $array) {
            for($i = 1; $i <= 5; $i++) {
                DB::table('rooster')->insert([
                    'start_time' => rand(8, 12).':00:00',
                    'end_time' => rand(13, 18).':00:00',
                    'comment' => 'Seeded Comment',
                    'from_home' => 0,
                    'weekdays' => $i,
                    'user_id' => 2,
                    'start_week' => $array[0],
                    'end_week' => $array[1],
                    'disabled' => 0,
                    'start_year' => 2021,
                    'end_year' => 2021,
                ]);
            }
        }
        foreach($arrays as $array) {
            for($i = 1; $i <= 5; $i++) {
                DB::table('rooster')->insert([
                    'start_time' => '08:30:00',
                    'end_time' => '17:00:00',
                    'comment' => 'Seeded Comment',
                    'from_home' => 0,
                    'weekdays' => $i,
                    'user_id' => 1,
                    'start_week' => $array[0],
                    'end_week' => $array[1],
                    'disabled' => 0,
                    'start_year' => 2021,
                    'end_year' => 2021,
                ]);
            }
        }
    }
}
