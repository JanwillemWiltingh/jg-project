<?php

namespace App\Console\Commands;

use App\Models\Clock;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ClockOutUsersAfterHours extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:clock-out';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clock all users out who are clocked in';

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
        $users = User::all();

        foreach ($users as $user) {
            $last_clock = $user->clocks()->get()->last();

            if($last_clock != null) {
                if($last_clock['end_time'] == null) {
                    $time = Carbon::now()->addHours(Clock::ADD_HOURS)->format('H:i');

                    if($last_clock['comment'] != null) {
                        $last_clock->update([
                            'end_time' => $time,
                            'comment' => $last_clock['comment'].'|<a style="color: red">Niet optijd uitgeklokt</a>'
                        ]);
                    } else {
                        $last_clock->update([
                            'end_time' => $time,
                            'comment' => '<a style="color: red">Niet optijd uitgeklokt</a>'
                        ]);
                    }

                }
            }
        }
        return Command::SUCCESS;
    }
}
