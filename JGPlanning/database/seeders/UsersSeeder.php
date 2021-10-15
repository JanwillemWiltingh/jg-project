<?php
namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('users')->insert([
            'name' => 'robert',
            'email' => 'robert@gmail.com',
            'password' => Hash::make('123'),
            'role_id' => Role::$roles['maintainer'],
        ]);

        DB::table('users')->insert([
            'name' => 'willem',
            'email' => 'mail@mail.com',
            'password' => Hash::make('welkom'),
            'role_id' => Role::$roles['maintainer'],
        ]);
    }
}
