<?php
namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Exception;
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
     * @throws Exception
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('users')->insert([
            'firstname' => 'Beheerder',
            'lastname' => 'Backup',
            'email' => 'planning@jgwebmarketing.nl',
            'created_at' => Carbon::now(),
            'password' => Hash::make('jgrooster1243'),
            'phone_number' => '0612345678',
            'role_id' => Role::getRoleID('maintainer'),
        ]);
        DB::table('users')->insert([
            'firstname' => 'Gobi',
            'lastname' => 'Suganakumar',
            'email' => 'gobi@jgwebmarketing.nl',
            'created_at' => Carbon::now(),
            'password' => Hash::make('jgrooster1243'),
            'phone_number' => '0612345678',
            'role_id' => Role::getRoleID('maintainer'),
        ]);
    }
}
