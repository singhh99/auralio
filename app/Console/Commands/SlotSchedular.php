<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use DateTime;

class SlotSchedular extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Slot:Schedular';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For slot management';

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
      // $today_date=date('d-m-Y '); 
      // $data=DB::table('customer_booking')->where('customer_schedule_date',$today_date)
      //                                     ->whereNull('customer_booking_date')
      //                                     ->where('booking_status_id', '!=' , 2)
      //                                     ->update(['customer_booking_date'=>$today_date]);



        $today_date=date('d-m-Y ');

        //for renewing saloon total slots ,avilable slot  in saloon table  and total slots in saloon_slots
        $saloon=DB::table('saloons')->get();
        for($j=0 ; $j<count($saloon);$j++)
        {
            $total_seats=$saloon[$j]->saloon_total_seats;
            $start_time = strtotime($saloon[$j]->saloon_time_from);
            $end_time   = strtotime($saloon[$j]->saloon_time_to);
            $diff = ($end_time - $start_time)/60;
            $total_slots= ($diff/30)*($total_seats);

            DB::table('saloons')->where('saloon_id',$saloon[$j]->saloon_id)
                                ->update(['saloon_total_slots'    => $total_slots,
                                          'saloon_avilable_seats' =>$saloon[$j]->saloon_total_seats]); 
            DB::table('saloon_slots')->where('saloon_id',$saloon[$j]->saloon_id)
                                     ->update(['total_seats'    => $total_seats]);                                  
        }
        
        /* for updation  of date in customer booking*/
        $data1=DB::table('customer_booking')->where('customer_schedule_date',$today_date)
                                            ->whereNull('customer_booking_date')
                                            ->where('booking_status_id', '!=' , 2)
                                            ->update(['customer_booking_date'=>$today_date])->get();
       
        /*for maintaing saloon_slots and saloons avilable and total slots */
        $booking_deatils=DB::table('customer_booking')->where('customer_schedule_date',$today_date)
                                           ->whereNull('customer_booking_date')
                                           ->where('booking_status_id', '!=' , 2)->get();
                                          
        for($i = 0; $i < count($booking_deatils); $i++)  
        {
            DB::table('saloon_slots')
                          ->where('saloon_slot_id',$booking_deatils[$i]->saloon_slot_id)
                          ->where('saloon_id',$booking_deatils[$i]->saloon_id)              
                          ->update(['total_seats' => DB::raw('total_seats - 1')]);
            DB::table('saloons')->where('saloon_id',$booking_deatils[$i]->saloon_id)
                                ->update(['saloon_total_slots'    => DB::raw('saloon_total_slots - 1'),
                                          'saloon_avilable_seats' => DB::raw('saloon_avilable_seats - 1')]);                  
        }
        // return 0;
    }
}
