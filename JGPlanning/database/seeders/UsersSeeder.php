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
            'firstname' => 'Robert',
            'lastname' => 'Polman',
            'email' => 'robertpolman1217@gmail.com',
            'password' => Hash::make('123'),
            'role_id' => Role::getRoleID('maintainer'),
        ]);

        DB::table('users')->insert([
            'firstname' => 'Jan-Willem',
            'lastname' => 'Willtigh',
            'email' => 'mail@mail.com',
            'password' => Hash::make('welkom'),
            'role_id' => Role::getRoleID('maintainer'),
        ]);

        DB::table('users')->insert([
            'firstname' => 'Barend',
            'lastname' => 'Noordhoff',
            'email' => 'barend@gmail.com',
            'password' => Hash::make('welkom'),
            'role_id' => Role::getRoleID('admin'),
        ]);

        DB::table('users')->insert([
            'firstname' => 'Hugo',
            'lastname' => 'De Goot',
            'email' => 'hugo@jgwebmarketing.nl',
            'password' => Hash::make('Hugo123@'),
            'role_id' => Role::getRoleID('employee'),
        ]);

        DB::table('users')->insert([
            'firstname' => 'Gobi',
            'lastname' => 'Achternaam',
            'email' => 'gobi@jgwebmarketing.nl',
            'password' => Hash::make('Gobi123@'),
            'role_id' => Role::getRoleID('admin'),
        ]);

        DB::table('users')->insert([
            'firstname' => 'Sander',
            'lastname' => 'Gehring',
            'email' => 'sander@jgwebmarketing.nl',
            'password' => Hash::make('Sander123@'),
            'role_id' => Role::getRoleID('admin'),
        ]);

        DB::table('users')->insert([
            'firstname' => 'Cas',
            'lastname' => 'Achternaam',
            'email' => 'cas@jgwebmarketing.nl',
            'password' => Hash::make('Cas123@'),
            'role_id' => Role::getRoleID('employee'),
        ]);

        DB::table('users')->insert([
            'firstname' => 'Brian',
            'lastname' => 'Achternaam',
            'email' => 'brian@jgwebmarketing.nl',
            'password' => Hash::make('Brian123@'),
            'role_id' => Role::getRoleID('employee'),
        ]);

        DB::table('users')->insert([
            'firstname' => 'Nick',
            'lastname' => 'Achternaam',
            'email' => 'nick@jgwebmarketing.nl',
            'password' => Hash::make('Nick123@'),
            'role_id' => Role::getRoleID('employee'),
        ]);
    }
}
