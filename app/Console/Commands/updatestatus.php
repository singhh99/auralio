<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class updatestatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'to change booking status every minute';

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
        DB::table('booking_statuses')->where('booking_status_id',11)->update(['booking_status_id'=>111]);
        return 0;
    }
}
