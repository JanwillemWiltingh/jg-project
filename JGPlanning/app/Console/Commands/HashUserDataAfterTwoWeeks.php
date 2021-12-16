<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HashUserDataAfterTwoWeeks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hash:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hash the user after its been deleted for two weeks';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $users = User::all()->where('deleted_at', '!=', null);
        foreach ($users as $user){
            $now = Carbon::now();
            $date1 = new DateTime($now);
            $date2 = new DateTime($user->deleted_at);
            $interval = $date1->diff($date2);
            $days = $interval->format('%a');

            if($days >= 14){
//                Hash::make($user->firstname);
//                Hash::make($user->middlename);
//                Hash::make($user->lastname);
//                Hash::make($user->email);
//                Hash::make($user->phone_number);
//                Hash::make($user->firstname);
            }

        }
            return Command::SUCCESS;
    }
}
