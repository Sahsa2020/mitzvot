<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use DB;
use App\Models\Box;
class ResetDailyDepositsAll extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ResetDailyDepositsAll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This job posts images to facebook page.';

        /**
     * Create a new command instance.
     *
     * @param
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
      DB::table('cbox_boxes')->update(['d_count' => 0]);
    }
}
