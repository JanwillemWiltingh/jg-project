<?php

namespace App\Console\Commands;

use App\Models\Rooster;
use Illuminate\Console\Command;

class MakeRoosterFinalizedEveryWeek extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rooster:finalize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finalize elke rooster die hiervoor gemaakt is.';

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
        $all_rooster = Rooster::all();
        foreach ($all_rooster as $ar)
        {
            if (!$ar->finalized)
            {
                $rooster_update = Rooster::all()
                    ->where('id', $ar->id)
                    ->first();
                $rooster_update->finalized = true;
                $rooster_update->save();
            }
            echo $ar->finalized;
        }
        return Command::SUCCESS;
    }
}
