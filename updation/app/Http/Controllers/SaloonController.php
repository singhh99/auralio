<?php

namespace App\Http\Controllers;

use App\SaloonModel;
use Illuminate\Http\Request;
use  App\Http\Controllers\CommonController;
use DB; 
use File;
use Session;
use Carbon\Carbon;
use Route;
use Validator;

class SaloonController extends Controller
{
    
    public function __construct(Request $request)
    {
     $this->CommonController=new CommonController();
    }

   
    public function index()
    {
     $saloon_list=SaloonModel::all();
      return view('saloon.index',compact('saloon_list'));
    }
 
    public function create()
    {
      $saloon_list['type_list']= DB::table('saloon_types')->get();
      $saloon_list['feature_list']= DB::table('features')->get(); 
      return view('saloon.add_saloon',$saloon_list);
    }

    public function store(Request $request)
    {
        $duration = '30';
        $total_seats=$request->saloon_total_seats;
        $start_time = strtotime($request->saloon_time_from);
        $end_time = strtotime($request->saloon_time_to);
        $diff = ($end_time - $start_time)/60;
        $total_slots= ($diff/30)*($total_seats);
        $array_of_time = array ();
        $add_mins  = $duration * 60;
        // if($start_time == $end_time || $start_time > $end_time)
        // {
        //     Session::flash('message', 'Please Check Start Time and End Time');
        //     return redirect()->action('SaloonController@index');
        // }
        // dd($start_time.','.$end_time);
        while ($start_time <= $end_time) 
        {
           $array_of_time[] = date("h:i", $start_time);
           $start_time += $add_mins;
        }
       $messages = [
        'required' => 'The :attribute field is required.',
        'saloon_feature_id.required' => 'The Availability field is required.'];

        $validatedData = $request->validate([
            'owner_name' => ['required', 'string', 'min:2','regex:/^[a-zA-Z ]*$/'],
            'owner_email' => ['required','max:255','email'],
            'owner_mobile' => ['required', 'string', 'max:255','regex:/^([987]{1})(\d{1})(\d{8})$/' ],
            'owner_pan_number' => ['required','string', 'max:10','min:10'],
            'owner_bank_name' => ['required', 'string', 'max:255'],
            'owner_IFSC_code' => ['required','string','max:11' ,'min:11'],
            'owner_account_number' => ['required', 'string', 'max:255'],
            'saloon_name' => ['required', 'string', 'max:255','regex:/^[a-zA-Z ]*$/'],
            'saloon_area' => ['required', 'string', 'max:255'],
            'saloon_address' => ['required', 'string', 'max:255'],
            'saloon_time_from' => ['required', 'string', 'max:255'],
            'saloon_time_to' => ['required', 'string', 'max:255'],
            'saloon_total_seats' => ['required', 'max:255'],
            'saloon_feature_id'=> ['required'],      
        ], $messages);

    if($request->file('owner_image'))
    {
        $owner_image = $this->CommonController->upload_image($request->file('owner_image'),"owner_image");
    }
    else
    {
        $owner_image = '';
    }
    $saloon_id=SaloonModel::insertGetId(['owner_name'=>$request->owner_name,
                             'owner_email'=>$request->owner_email,
                             'owner_mobile'=>$request->owner_mobile,
                             'owner_pan_number'=>$request->owner_pan_number,
                             'owner_email'=>$request->owner_email,
                             'owner_bank_name'=>$request->owner_bank_name,
                             'owner_IFSC_code'=>$request->owner_IFSC_code,
                             'owner_account_number'=>$request->owner_account_number,
                             'saloon_name'=>$request->saloon_name,
                             'saloon_type_id'=>$request->saloon_type_id,
                             'saloon_area'=>$request->saloon_area,
                             'saloon_address'=>$request->saloon_address,
                             'saloon_lattitude'=>$request->saloon_lattitude,
                             'saloon_longitude'=>$request->saloon_longitude,
                             'saloon_working_days'=>$request->saloon_working_days ? json_encode($request->saloon_working_days) : '',
                             'saloon_time_from'=>$request->saloon_time_from,
                             'saloon_time_to'=>$request->saloon_time_to,
                             'saloon_total_seats'=>$request->saloon_total_seats,
                             'saloon_avilable_seats'=>$total_seats,
                             'saloon_total_slots'=>$total_slots,
                             'owner_image'=>$owner_image,
                             'saloon_feature_id'=>json_encode($request->saloon_feature_id),
                            
                         ]);
            // for loop to insert data in saloon images
            for($i = 0; $i < count($request->file('saloon_image')); $i++)
            {
                $saloon_image = $this->CommonController->upload_image($request->file('saloon_image')[$i],"saloon_image");
                $data[] = ['saloon_id' => $saloon_id, 'saloon_image' => $saloon_image];
            }
            DB::table('saloons_images')->insert($data);

          //  for loop to insert  data in saloon service
        
            for($j = 0; $j < count($request->service_name); $j++)
            {
                $data1[] = ['saloon_id' => $saloon_id, 'service_name' => $request->service_name[$j], 'service_price' => $request->service_price[$j]];
            }
            DB::table('saloons_services')->insert($data1);

        //for loop to insert data in  saloon slots
             for($z = 0; $z < count($array_of_time) - 1; $z++)
            {
             // $new_array_of_time[] = '' . $array_of_time[$z] . ' - ' . $array_of_time[$z + 1];
             $data2[]=['saloon_id'=>$saloon_id,'slot_from'=>$array_of_time[$z],'slot_to'=>$array_of_time[$z + 1],'total_seats'=>$total_seats];   
            }
            DB::table('saloon_slots')->insert($data2);
              Session::flash('message', 'Your Data save Successfully');
               return redirect()->action('SaloonController@index');
            
    }

    public function show(SaloonModel $saloonModel)
    {
       
    }

    public function edit($saloon_id)
    {
        $saloon_list['type_list']= DB::table('saloon_types')->get();
        $saloon_list['feature_list']= DB::table('features')->get();
        $saloon_list['saloon_details']=SaloonModel::where('saloon_id',$saloon_id)->get();
        return view('saloon.edit_saloon',$saloon_list);
    }

    public function update(Request $request,$saloon_id)
    { 

       $messages = [
        'required' => 'The :attribute field is required.',
        'saloon_feature_id.required' => 'The Availability field is required.'];

        $validatedData = $request->validate([
            'owner_name' => ['required', 'string', 'min:2','regex:/^[a-zA-Z ]*$/'],
            'owner_email' => ['required','max:255','email'],
            'owner_mobile' => ['required', 'string', 'max:255','regex:/^([987]{1})(\d{1})(\d{8})$/' ],
            'owner_pan_number' => ['required','string', 'max:10','min:10'],
            'owner_bank_name' => ['required', 'string', 'max:255'],
            'owner_IFSC_code' => ['required','string','max:11' ,'min:11'],
            'owner_account_number' => ['required', 'string', 'max:255'],
            'saloon_name' => ['required', 'string', 'max:255','regex:/^[a-zA-Z ]*$/'],
            'saloon_area' => ['required', 'string', 'max:255'],
            'saloon_address' => ['required', 'string', 'max:255'],
            'saloon_time_from' => ['required', 'string', 'max:255'],
            'saloon_time_to' => ['required', 'string', 'max:255'],
            'saloon_total_seats' => ['required', 'max:255'],
            'saloon_feature_id'=> ['required'],      
        ], $messages);

        $duration = '30';
        $total_seats=$request->saloon_total_seats;
        $start_time = strtotime($request->saloon_time_from);
        $end_time = strtotime($request->saloon_time_to);
        $diff = ($end_time - $start_time)/60;
        $total_slots= ($diff/30)*($total_seats);
        $array_of_time = array ();
        $add_mins  = $duration * 60;
        while ($start_time <= $end_time) 
        {
           $array_of_time[] = date ("h:i", $start_time);
           $start_time += $add_mins;
        }  

         $owner_image = $request->file('owner_image');
        if($owner_image == '')   
         {
          DB::table('saloon_slots')->where('saloon_id', $saloon_id)->delete();  
          DB::table('saloons')->where('saloon_id',$saloon_id)->update([
                            'owner_name'=>$request->owner_name,
                             'owner_email'=>$request->owner_email,
                             'owner_mobile'=>$request->owner_mobile,
                             'owner_pan_number'=>$request->owner_pan_number,
                             'owner_email'=>$request->owner_email,
                             'owner_bank_name'=>$request->owner_bank_name,
                             'owner_IFSC_code'=>$request->owner_IFSC_code,
                             'owner_account_number'=>$request->owner_account_number,
                             'saloon_name'=>$request->saloon_name,
                             'saloon_type_id'=>$request->saloon_type_id,
                             'saloon_area'=>$request->saloon_area,
                            'saloon_lattitude'=>$request->saloon_lattitude,
                             'saloon_longitude'=>$request->saloon_longitude,
                             'saloon_address'=>$request->saloon_address,
                             'saloon_working_days'=>json_encode($request->saloon_working_days),
                             'saloon_time_from'=>$request->saloon_time_from,
                             'saloon_time_to'=>$request->saloon_time_to,
                             'saloon_total_seats'=>$request->saloon_total_seats,
                             'saloon_total_slots'=>$total_slots,
                             'owner_image'=>$owner_image,
                             'saloon_feature_id'=>json_encode($request->saloon_feature_id),
                            
                         ]);
          //for loop to insert data in  saloon slots
             for($z = 0; $z < count($array_of_time) - 1; $z++)
            {
             // $new_array_of_time[] = '' . $array_of_time[$z] . ' - ' . $array_of_time[$z + 1];
             $data2[]=['saloon_id'=>$saloon_id,'slot_from'=>$array_of_time[$z],'slot_to'=>$array_of_time[$z + 1],'total_seats'=>$total_seats];   
            }
            DB::table('saloon_slots')->insert($data2);
      }
      else{
          // $validatedData = $request->validate(['EngineerDocuments' => ['mimes:pdf','max:10240'] ]);
            DB::table('saloon_slots')->where('saloon_id', $saloon_id)->delete();
            $data = SaloonModel::where('saloon_id', $saloon_id)->get();
            $image_path = public_path('/images/owner_image/'.$data[0]->owner_image);
            if(File::exists($image_path))
            {
                File::delete($image_path);
            }
             $owner_image = $this->CommonController->upload_image($request->file('owner_image'),"owner_image");
              DB::table('saloons')->where('saloon_id',$saloon_id)->update(['owner_name'=>$request->owner_name,
                             'owner_email'=>$request->owner_email,
                             'owner_mobile'=>$request->owner_mobile,
                             'owner_pan_number'=>$request->owner_pan_number,
                             'owner_email'=>$request->owner_email,
                             'owner_bank_name'=>$request->owner_bank_name,
                             'owner_IFSC_code'=>$request->owner_IFSC_code,
                             'owner_account_number'=>$request->owner_account_number,
                             'saloon_name'=>$request->saloon_name,
                             'saloon_type_id'=>$request->saloon_type_id,
                             'saloon_area'=>$request->saloon_area,
                             'saloon_address'=>$request->saloon_address,
                             'saloon_working_days'=>json_encode($request->saloon_working_days),
                             'saloon_time_from'=>$request->saloon_time_from,
                             'saloon_time_to'=>$request->saloon_time_to,
                             'saloon_total_seats'=>$request->saloon_total_seats,
                             'owner_image'=>$owner_image,
                             'saloon_feature_id'=>json_encode($request->saloon_feature_id),
                            
                         ]);
              //for loop to insert data in  saloon slots
             for($z = 0; $z < count($array_of_time) - 1; $z++)
            {
             $data2[]=['saloon_id'=>$saloon_id,'slot_from'=>$array_of_time[$z],'slot_to'=>$array_of_time[$z + 1],'total_seats'=>$total_seats];   
            }
            DB::table('saloon_slots')->insert($data2);
      }
        return redirect()->action('SaloonController@index');
    }
   
    public function service_list(Request $request)
    {
       $id = $request->saloon_id;
       $service = DB::table('saloons_services')->where('saloon_id', $request->saloon_id)->get(); 
       return $this->CommonController->successResponse($service,' Data fectched succesfuly',200);
    }
     
       public function validateServices($request)
    {
       
        return Validator::make($request->all(), [
             'saloon_id' => ['required'],
             'service_name'=>['required','string'],
             'service_price'=>['required']                           
            ]);
    }

    // function to add  service
     public function service_store(Request $request)
    {
      if($request->Source=='Web')
      {
            $data=['service_name'=>$request->service_name,
                                  'service_price'=>$request->service_price,
                                  'saloon_id'=>$request->saloon_id ];
            DB::table('saloons_services')->insert($data);
            Session::flash('message', 'Your Data save Successfully');
           return redirect()->action('SaloonController@index');
      }
       else {

              $validator = $this->validateServices($request);
              if($validator->fails())
            {
                return $this->CommonController->errorResponse($validator->messages(), 422);
            }
            else
            {
               $data=['service_name'=>$request->service_name,
                                      'service_price'=>$request->service_price,
                                      'saloon_id'=>$request->saloon_id ];
                DB::table('saloons_services')->insert($data);
                return $this->CommonController->successResponse(null,' Data Saved succesfuly',200);     
           }
        }
    }
     
      //function to edit saloon service
    public function edit_service(Request $request)
    {
        $service_id=$request->saloon_service_id;
        $service=DB::table('saloons_services')->where('saloon_service_id',$request->saloon_service_id)->get(); 
       return response($service);
    }

      public function validateUpServices($request)
    {
        return Validator::make($request->all(), [
             'saloon_id' => ['required'],
             'service_name'=>['required','string'],
             'service_price'=>['required'] ,
             'saloon_service_id'=>['required']                          
            ]);
    }
     //function to update saloon service
    public function update_service(Request $request)
    {

         if($request->Source=='Web')
         {
             DB::table('saloons_services')->where('saloon_service_id',$request->saloon_service_id)->update([
                                     'saloon_id'=>$request->saloon_id,
                                     'service_name'=>$request->service_name,
                                     'service_price'=>$request->service_price]);
              Session::flash('message', 'Your Data updated Successfully');
             return redirect()->action('SaloonController@index');
         }
         else
         {
            $validator = $this->validateUpServices($request);
              if($validator->fails())
            {
                return $this->CommonController->errorResponse($validator->messages(), 422);
            }
            else
            {
               DB::table('saloons_services')->where('saloon_service_id',$request->saloon_service_id)->update([
                                     'saloon_id'=>$request->saloon_id,
                                     'service_name'=>$request->service_name,
                                     'service_price'=>$request->service_price]);
                return $this->CommonController->successResponse(null,' Data Saved succesfuly',200);
            }

         }
    }

 //function to delete  saloon service 
    public function delete_service(Request $request)
    {
       $id = $request->saloon_service_id;
        DB::table('saloons_services')->where('saloon_service_id', $id)->delete();
      return $this->CommonController->successResponse($id,'Data Delete succesfuly',200);
    }

   
    //function for admin approval 
     public function admin_approval(Request $request)
     {
        $saloon_id = $request->saloon_id;
        $status_update= DB::table('saloons')->where('saloon_id',$saloon_id)->update(['admin_approval'=>$request->admin_approval]);
     }

     //login api for saloon

    public function saloon_login(Request $request)
    {
        // $owner_mobile=7835833922;
        $owner_mobile=$request->data['owner_mobile'];
        dd( $owner_mobile);
        $otp = mt_rand(1000,9999);
        // session(['owner_mobile'=> $owner_mobile]);
        // session(['otp' => $otp]);
        // $msg = "Dear Saloon vendor,$otp is your one time password , please enter the otp to proceed.Thank You. ";
        // $curl = curl_init();
        // curl_setopt_array($curl, array(
        //   CURLOPT_URL => 'http://103.127.157.58/vendorsms/pushsms.aspx?clientid=cbffd407-7347-4cf3-bbcd-c7e224550458&apikey=cf3864a4-b05c-45bc-bd32-6fb39619ed3d&msisdn=91'. $owner_mobile.'&sid=ONETIC&msg='.urlencode($msg).'&fl=0&gwid=2',
        //   CURLOPT_RETURNTRANSFER => true,
        //   CURLOPT_ENCODING => "",
        //   CURLOPT_MAXREDIRS => 10,
        //   CURLOPT_TIMEOUT => 30,
        //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //   CURLOPT_CUSTOMREQUEST => "GET",
        //   CURLOPT_HTTPHEADER => array(
        //     "cache-control: no-cache",
        //     "content-type: application/x-www-form-urlencoded",
            
        //   ),
        // ));
        // $response = curl_exec($curl);
        // $err = curl_error($curl);

        // curl_close($curl);

        // if ($err) {
        //  echo "cURL Error #:" . $err;
        // } else {
        //  echo $response;
         
        // }
         // return $this->CommonController->successResponse($owner_mobile,' Data Saved succesfuly',200);
    }
     //function for api fields validation
      public function validateVendor($request)
    {
       $messages = [
        'required' => 'The :attribute field is required.',
        'saloon_feature_id.required' => 'The Availability field is required.'];
        return Validator::make($request->all(), [
             'owner_name' => ['required', 'string', 'min:2','regex:/^[a-zA-Z ]*$/'],
            'owner_email' => ['required','max:255','email'],
            'owner_mobile' => ['required', 'string', 'max:255','regex:/^([987]{1})(\d{1})(\d{8})$/' ],
            'owner_pan_number' => ['required','string', 'max:10','min:10'],
            'owner_bank_name' => ['required', 'string', 'max:255'],
            'owner_IFSC_code' => ['required','string','max:11' ,'min:11'],
            'owner_account_number' => ['required', 'string', 'max:255'],
            'saloon_name' => ['required', 'string', 'max:255','regex:/^[a-zA-Z ]*$/'],
            'saloon_area' => ['required', 'string', 'max:255'],
            'saloon_address' => ['required', 'string', 'max:255'],
            'saloon_time_from' => ['required', 'string', 'max:255'],
            'saloon_time_to' => ['required', 'string', 'max:255'],
            'saloon_total_seats' => ['required', 'max:255'],
            'saloon_type_id'=>['required'],
            'saloon_working_days'=>['required'],
            'saloon_feature_id'=> ['required'],      
            ],$messages );
    }

     public function vendor_registartion(Request $request)
     {
        $validator = $this->validateVendor($request);

        if($validator->fails())
        {
            return $this->CommonController->errorResponse($validator->messages(), 422);
        }
        else
        {
         $duration = '30';
        $total_seats=$request->saloon_total_seats;
        $start_time = strtotime($request->saloon_time_from);
        $end_time = strtotime($request->saloon_time_to);
        $diff = ($end_time - $start_time)/60;
        $total_slots= ($diff/30)*($total_seats);
        $array_of_time = array ();
        $add_mins  = $duration * 60;
        while ($start_time <= $end_time) 
        {
       $array_of_time[] = date ("h:i", $start_time);
       $start_time += $add_mins;
       }
     if($request->file('owner_image'))
    {
        $owner_image = $this->CommonController->upload_image($request->file('owner_image'),"owner_image");
    }
    else
    {
        $owner_image = '';
    }
    $saloon_id=SaloonModel::insertGetId(['owner_name'=>$request->owner_name,
                             'owner_email'=>$request->owner_email,
                             'owner_mobile'=>$request->owner_mobile,
                             'owner_pan_number'=>$request->owner_pan_number,
                             'owner_email'=>$request->owner_email,
                             'owner_bank_name'=>$request->owner_bank_name,
                             'owner_IFSC_code'=>$request->owner_IFSC_code,
                             'owner_account_number'=>$request->owner_account_number,
                             'saloon_name'=>$request->saloon_name,
                             'saloon_type_id'=>$request->saloon_type_id,
                             'saloon_area'=>$request->saloon_area,
                             'saloon_address'=>$request->saloon_address,
                             'saloon_lattitude'=>$request->saloon_lattitude,
                             'saloon_longitude'=>$request->saloon_longitude,
                             'saloon_working_days'=>$request->saloon_working_days,
                             'saloon_time_from'=>$request->saloon_time_from,
                             'saloon_time_to'=>$request->saloon_time_to,
                             'saloon_total_seats'=>$request->saloon_total_seats,
                             'saloon_avilable_seats'=>$total_seats,
                             'saloon_total_slots'=>$total_slots,
                             'owner_image'=>$owner_image,
                             'saloon_feature_id'=>$request->saloon_feature_id,
                            
                         ]);
            // for loop to insert data in saloon images
            for($i = 0; $i < count($request->file('saloon_image')); $i++)
            {
                $saloon_image = $this->CommonController->upload_image($request->file('saloon_image')[$i],"saloon_image");
                $data[] = ['saloon_id' => $saloon_id, 'saloon_image' => $saloon_image];
            }
            DB::table('saloons_images')->insert($data);

          //  for loop to insert  data in saloon service
         $total_service=json_decode($request->service_name);
          $total_price=json_decode($request->service_price);
          for($j = 0; $j < count($total_service); $j++)
            {
                $data1[] = ['saloon_id' =>$saloon_id, 'service_name' => $total_service[$j], 'service_price' => $total_price[$j]];
            }
            DB::table('saloons_services')->insert($data1);

        //for loop to insert data in  saloon slots
             for($z = 0; $z < count($array_of_time) - 1; $z++)
            {
             // $new_array_of_time[] = '' . $array_of_time[$z] . ' - ' . $array_of_time[$z + 1];
             $data2[]=['saloon_id'=>$saloon_id,'slot_from'=>$array_of_time[$z],'slot_to'=>$array_of_time[$z + 1],'total_seats'=>$total_seats];   
            }
            DB::table('saloon_slots')->insert($data2);
              return $this->CommonController->successResponse(null,' Data Saved succesfuly',200);   
     }
 }


     public function vendor_update(Request $request,$saloon_id)
     {

        $validator = $this->validateVendor($request);

        if($validator->fails())
        {
            return $this->CommonController->errorResponse($validator->messages(), 422);
        }
        else
        {
            $duration = '30';
        $total_seats=$request->saloon_total_seats;
        $start_time = strtotime($request->saloon_time_from);
        $end_time = strtotime($request->saloon_time_to);
        $diff = ($end_time - $start_time)/60;
        $total_slots= ($diff/30)*($total_seats);
        $array_of_time = array ();
        $add_mins  = $duration * 60;
        while ($start_time <= $end_time) 
        {
           $array_of_time[] = date ("h:i", $start_time);
           $start_time += $add_mins;
        }  

         $owner_image = $request->file('owner_image');
        if($owner_image == '')   
         {
          DB::table('saloon_slots')->where('saloon_id', $saloon_id)->delete();  
          DB::table('saloons')->where('saloon_id',$saloon_id)->update([
                            'owner_name'=>$request->owner_name,
                             'owner_email'=>$request->owner_email,
                             'owner_mobile'=>$request->owner_mobile,
                             'owner_pan_number'=>$request->owner_pan_number,
                             'owner_email'=>$request->owner_email,
                             'owner_bank_name'=>$request->owner_bank_name,
                             'owner_IFSC_code'=>$request->owner_IFSC_code,
                             'owner_account_number'=>$request->owner_account_number,
                             'saloon_name'=>$request->saloon_name,
                             'saloon_type_id'=>$request->saloon_type_id,
                             'saloon_area'=>$request->saloon_area,
                            'saloon_lattitude'=>$request->saloon_lattitude,
                             'saloon_longitude'=>$request->saloon_longitude,
                             'saloon_address'=>$request->saloon_address,
                             'saloon_working_days'=>$request->saloon_working_days,
                             'saloon_time_from'=>$request->saloon_time_from,
                             'saloon_time_to'=>$request->saloon_time_to,
                             'saloon_total_seats'=>$request->saloon_total_seats,
                             'saloon_total_slots'=>$total_slots,
                             'owner_image'=>$owner_image,
                             'saloon_feature_id'=>$request->saloon_feature_id,
                            
                         ]);
          //for loop to insert data in  saloon slots
             for($z = 0; $z < count($array_of_time) - 1; $z++)
            {
             // $new_array_of_time[] = '' . $array_of_time[$z] . ' - ' . $array_of_time[$z + 1];
             $data2[]=['saloon_id'=>$saloon_id,'slot_from'=>$array_of_time[$z],'slot_to'=>$array_of_time[$z + 1],'total_seats'=>$total_seats];   
            }
            DB::table('saloon_slots')->insert($data2);
      }
      else{
          // $validatedData = $request->validate(['EngineerDocuments' => ['mimes:pdf','max:10240'] ]);
            DB::table('saloon_slots')->where('saloon_id', $saloon_id)->delete();
            $data = SaloonModel::where('saloon_id', $saloon_id)->get();
            $image_path = public_path('/images/owner_image/'.$data[0]->owner_image);
            if(File::exists($image_path))
            {
                File::delete($image_path);
            }
             $owner_image = $this->CommonController->upload_image($request->file('owner_image'),"owner_image");
              DB::table('saloons')->where('saloon_id',$saloon_id)->update(['owner_name'=>$request->owner_name,
                             'owner_email'=>$request->owner_email,
                             'owner_mobile'=>$request->owner_mobile,
                             'owner_pan_number'=>$request->owner_pan_number,
                             'owner_email'=>$request->owner_email,
                             'owner_bank_name'=>$request->owner_bank_name,
                             'owner_IFSC_code'=>$request->owner_IFSC_code,
                             'owner_account_number'=>$request->owner_account_number,
                             'saloon_name'=>$request->saloon_name,
                             'saloon_type_id'=>$request->saloon_type_id,
                             'saloon_area'=>$request->saloon_area,
                             'saloon_address'=>$request->saloon_address,
                             'saloon_working_days'=>$request->saloon_working_days,
                             'saloon_time_from'=>$request->saloon_time_from,
                             'saloon_time_to'=>$request->saloon_time_to,
                             'saloon_total_seats'=>$request->saloon_total_seats,
                             'owner_image'=>$owner_image,
                             'saloon_feature_id'=>$request->saloon_feature_id,
                            
                         ]);
              //for loop to insert data in  saloon slots
             for($z = 0; $z < count($array_of_time) - 1; $z++)
            {
             $data2[]=['saloon_id'=>$saloon_id,'slot_from'=>$array_of_time[$z],'slot_to'=>$array_of_time[$z + 1],'total_seats'=>$total_seats];   
            }
            DB::table('saloon_slots')->insert($data2);  
      }
       return $this->CommonController->successResponse(null,'Data updated succesfuly',200);

     }
 }

}
