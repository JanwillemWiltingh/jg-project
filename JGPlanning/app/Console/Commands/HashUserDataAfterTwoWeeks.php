<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
                //hash everything except name and email
                $phone_number = Str::random(10);
                $middlename = Str::random(15);
                $lastname = Str::random(15);
                $user->update([
                    'middlename' => $middlename,
                    'lastname' => $lastname,
                    'phone_number' => $phone_number
                ]);
            }
            if($days >= 730){
                $user->delete();
            }

        }
            return Command::SUCCESS;
    }
}
