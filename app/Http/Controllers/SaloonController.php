<?php
 
namespace App\Http\Controllers;

use App\SaloonModel;
use Illuminate\Http\Request;
use  App\Http\Controllers\CommonController;
use DB;
use File;
use Illuminate\Support\Facades\Log;
use Session;
use Carbon\Carbon;
use Route;
use Validator;

class SaloonController extends Controller
{ 

    public function __construct(Request $request)
    {
     $this->middleware('permission', ['only' => ['create', 'edit','store','index','update','destroy','admin_approval']]);
     $this->CommonController=new CommonController();
    }


    public function index()
    {
     $this->middleware('permission');
     $service_list=DB::table('services')->get();
     $saloon_list=SaloonModel::where('saloon_status',0)->get();
     // saloon_list=SaloonModel::all();
      return view('saloon.index',compact('saloon_list','service_list'));
    }

    public function create()
    {
      $this->middleware('permission');
      $saloon_list['service_list']=DB::table('services')->get();
      $saloon_list['days_list']=DB::table('days')->get();
      $saloon_list['type_list']= DB::table('saloon_types')->get();
      $saloon_list['feature_list']= DB::table('features')->get();
      return view('saloon.add_saloon',$saloon_list);
    }

    public function store(Request $request)
    {
        $this->middleware('permission');
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
           $array_of_time[] = date("h:i A", $start_time);
           $start_time += $add_mins;
        }
       $messages = [
        'required' => 'The :attribute field is required.',
        'saloon_feature_id.required' => 'The Availability field is required.',
        'saloon_working_days.required' => 'The working days field is required.'];

        $validatedData = $request->validate([
            'owner_name' => ['required', 'string', 'min:2','regex:/^[a-zA-Z ]*$/'],
            'owner_email' => ['required','max:255','email'],
            'owner_mobile' => ['required', 'string', 'max:255','regex:/^([987]{1})(\d{1})(\d{8})$/','unique:saloons' ],
            // 'owner_pan_number' => ['required','string', 'max:10','min:10'],
            // 'owner_bank_name' => ['required', 'string', 'max:255'],
            // 'owner_IFSC_code' => ['required','string','max:11' ,'min:11'],
            // 'owner_account_number' => ['required', 'string', 'max:255'],
            'saloon_name' => ['required', 'string', 'max:255','regex:/^[a-zA-Z0-9 ]*$/'],
            'saloon_area' => ['required', 'string', 'max:255'],
            'saloon_address' => ['required', 'string', 'max:255'],
            'saloon_time_from' => ['required', 'string', 'max:255'],
            'saloon_time_to' => ['required', 'string', 'max:255'],
            'saloon_total_seats' => ['required', 'max:255'],
            'saloon_feature_id'=> ['required'],
            'saloon_working_days'=>['required'],

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
                              'saloon_pincode'=>$request->saloon_pincode,
                             // 'saloon_working_days'=>$request->saloon_working_days ? json_encode($request->saloon_working_days) : '',
                             'saloon_time_from'=>$request->saloon_time_from,
                             'saloon_time_to'=>$request->saloon_time_to,
                             'saloon_total_seats'=>$request->saloon_total_seats,
                             'saloon_avilable_seats'=>$total_seats,
                             'saloon_total_slots'=>$total_slots,
                             'owner_image'=>$owner_image,
                             'saloon_feature_id'=>json_encode($request->saloon_feature_id),
                             'saloon_working_days'=>json_encode($request->saloon_working_days),

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
                $data1[] = ['saloon_id' => $saloon_id, 'service_name' => $request->service_name[$j], 'service_price' => $request->service_price[$j],'other'=>$request->other[$j]];
            }

            DB::table('saloons_services')->insert($data1);

        //for loop to insert data in  saloon slots
             for($z = 0; $z < count($array_of_time) - 1; $z++)
            {
             // $new_array_of_time[] = '' . $array_of_time[$z] . ' - ' . $array_of_time[$z + 1];
             $data2[]=['saloon_id'=>$saloon_id,'slot_from'=>$array_of_time[$z],'slot_to'=>$array_of_time[$z + 1],'total_seats'=>$total_seats];
            }
            DB::table('saloon_slots')->insert($data2);

            $saloon_code = ($saloon_id<10) ? 'V00'.$saloon_id : (($saloon_id >=10 && $saloon_id < 100) ? 'V0'.$saloon_id : 'V'.$saloon_id);
                DB::table('saloons')->where('saloon_id',$saloon_id)->update(['saloon_code'=>$saloon_code]);
            Session::flash('message', 'Your Data save Successfully');
            return redirect()->action('SaloonController@index');

    }

    public function edit($saloon_id)
    {
        $this->middleware('permission');
        $saloon_list['days_list']=DB::table('days')->get();
        $saloon_list['type_list']= DB::table('saloon_types')->get();
        $saloon_list['feature_list']= DB::table('features')->get();
        $saloon_list['saloon_details']=SaloonModel::where('saloon_id',$saloon_id)->get();
        return view('saloon.edit_saloon',$saloon_list);
    }

    public function update(Request $request,$saloon_id)
    {
      $this->middleware('permission');
       $messages = [
        'required' => 'The :attribute field is required.',
        'saloon_feature_id.required' => 'The Availability field is required.'];

        $validatedData = $request->validate([
            'owner_name' => ['required', 'string', 'min:2','regex:/^[a-zA-Z ]*$/'],
            'owner_email' => ['required','max:255','email'],
            'owner_mobile' => ['required', 'string', 'max:255','regex:/^([987]{1})(\d{1})(\d{8})$/','max:10','min:10' ],
            // 'owner_pan_number' => ['required','string', 'max:10','min:10'],
            // 'owner_bank_name' => ['required', 'string', 'max:255'],
            // 'owner_IFSC_code' => ['required','string','max:11' ,'min:11'],
            // 'owner_account_number' => ['required', 'string', 'max:255'],
            'saloon_name' => ['required', 'string', 'max:255','regex:/^[a-zA-Z0-9 ]*$/'],
            'saloon_area' => ['required', 'string', 'max:255'],
            'saloon_address' => ['required', 'string', 'max:255'],
            'saloon_time_from' => ['required', 'string', 'max:255'],
            'saloon_time_to' => ['required', 'string', 'max:255'],
            'saloon_total_seats' => ['required', 'max:255'],
            'saloon_feature_id'=> ['required'],
            'saloon_working_days'=> ['required'],

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
           $array_of_time[] = date ("h:i A", $start_time);
           $start_time += $add_mins;
        }

         $owner_image = $request->file('owner_image');
        if($owner_image == '')
         {
        //   DB::table('saloon_slots')->where('saloon_id', $saloon_id)->delete();
        DB::table('saloon_slots')->where('saloon_id', $saloon_id)->update(['status'=>'old']);
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
                              'saloon_pincode'=>$request->saloon_pincode,
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
             $data2[]=['saloon_id'=>$saloon_id,'slot_from'=>$array_of_time[$z],'slot_to'=>$array_of_time[$z + 1],'total_seats'=>$total_seats,'status'=>'new'];
            }
            DB::table('saloon_slots')->insert($data2);
      }
      else{
          // $validatedData = $request->validate(['EngineerDocuments' => ['mimes:pdf','max:10240'] ]);
            // DB::table('saloon_slots')->where('saloon_id', $saloon_id)->delete();
            DB::table('saloon_slots')->where('saloon_id', $saloon_id)->update(['status'=>'old']);
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
                              'saloon_pincode'=>$request->saloon_pincode,
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
             $data2[]=['saloon_id'=>$saloon_id,'slot_from'=>$array_of_time[$z],'slot_to'=>$array_of_time[$z + 1],'total_seats'=>$total_seats,'status'=>'new'];
            }
            DB::table('saloon_slots')->insert($data2);
      }
        return redirect()->action('SaloonController@index');
    }

   public function service_list(Request $request,$saloon_id,$web=Null)
    {
       if($web)
       {
         $this->middleware('permission');
         $id = $request->saloon_id;
         $service = DB::table('saloons_services')
            ->leftJoin('services', 'saloons_services.service_name', '=', 'services.service_id')
            ->select('saloons_services.*', 'services.service_name')
            ->where('saloons_services.saloon_id', $saloon_id)
            ->get();

       //$service = DB::table('saloons_services')->where('saloon_id', $request->saloon_id)->get();
       return $this->CommonController->successResponse($service,' Data fectched succesfuly',200);
       
      }
      else{
         $service = DB::table('saloons_services')
            ->leftJoin('services', 'saloons_services.service_name', '=', 'services.service_id')
            ->select('saloons_services.*', 'services.service_name')
             ->where('saloons_services.saloon_id', $saloon_id)
            ->get();
       //$service = DB::table('saloons_services')->where('saloon_id', $request->saloon_id)->get();
       return $this->CommonController->successResponse($service,' Data fectched succesfuly',200);
      }

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
            $this->middleware('permission');
            $data=['service_name'=>$request->service_name,
                   'service_price'=>$request->service_price,
                   'saloon_id'=>$request->saloon_id,
                   'other'=>$request->other
                    ];
            DB::table('saloons_services')->insert($data);
            Session::flash('message', 'Your Data save Successfully');
           return redirect()->action('SaloonController@index');
      }
       else {

              $validator = $this->validateServices($request);
              if($validator->fails())
            {
                return $this->CommonController->errorResponse($validator->messages(), 20);
            }
            else
            {
               $data=['service_name'=>$request->service_name,
                      'service_price'=>$request->service_price,
                      'saloon_id'=>$request->saloon_id,
                      'other'=>$request->other ];
                DB::table('saloons_services')->insert($data);
                return $this->CommonController->successResponse(null,' Data Saved succesfuly',200);
           }
        }
    }

      //function to edit saloon service
    public function edit_service(Request $request)
    {
         $service_id=$request->saloon_service_id;
         $service = DB::table('saloons_services')
            ->leftJoin('services', 'saloons_services.service_name', '=', 'services.service_id')
            ->select('saloons_services.*', 'services.service_name','services.service_id')
             ->where('saloons_services.saloon_service_id', $service_id)
            ->get();
        // $service_id=$request->saloon_service_id;
        // $service=DB::table('saloons_services')->where('saloon_service_id',$request->saloon_service_id)->get();
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
            $this->middleware('permission');
             DB::table('saloons_services')->where('saloon_service_id',$request->saloon_service_id)->update([
                                     'saloon_id'=>$request->saloon_id,
                                     'service_name'=>$request->service_name,
                                      'other'=>$request->other,
                                     'service_price'=>$request->service_price]);

              Session::flash('message', 'Your Data updated Successfully');
             return redirect()->action('SaloonController@index');
         }
         else
         {
            $validator = $this->validateUpServices($request);
              if($validator->fails())
            {
                return $this->CommonController->errorResponse($validator->messages(), 200);
            }
            else
            {
               DB::table('saloons_services')->where('saloon_service_id',$request->saloon_service_id)->update([
                                     'saloon_id'=>$request->saloon_id,
                                     'service_name'=>$request->service_name,
                                     'other'=>$request->other,
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
        $this->middleware('permission');
        $saloon_id = $request->saloon_id;

        $status_update= DB::table('saloons')->where('saloon_id',$saloon_id)->update(['admin_approval'=>$request->admin_approval]);

        if($status_update)
         {
            return response()->json("Saloon Status updated");
         }
         else
         {
            return response()->json("Saloon Status not updated");
         }


     }


     //function for api fields validation
      public function validateVendor($request)
    {
       $messages = [
        'required' => 'The :attribute field is required.',
        'saloon_feature_id.required' => 'The Availability field is required.'];
        return Validator::make($request->all(), [
             'owner_name' => ['required', 'string', 'min:2'],
            'owner_email' => ['required','max:255','email'],
            'owner_mobile' => ['required', 'string', 'max:255','regex:/^([987]{1})(\d{1})(\d{8})$/','max:10','min:10' ],
            // 'owner_pan_number' => ['string', 'max:10','min:10'],
            // 'owner_bank_name' => [ 'string', 'max:255'],
            // 'owner_IFSC_code' => ['string','max:11' ,'min:11'],
            // 'owner_account_number' => ['string', 'max:255'],
            'saloon_name' => ['required', 'string', 'max:255'],
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

     public function vendor_registartion(Request $request,$saloon_id)
     {
         Log::critical($request->input());
        $validator = $this->validateVendor($request);

        if($validator->fails())
        {
            return $this->CommonController->errorResponse($validator->messages(), 200);
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
       $array_of_time[] = date ("h:i A", $start_time);
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
     DB::table('saloons')->where('saloon_id',$saloon_id)
                         ->update(['owner_name'=>$request->owner_name,
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
                             'saloon_pincode'=>$request->saloon_pincode,
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
           $total_other=json_decode($request->other);

          for($j = 0; $j < count($total_service); $j++)
            {
                $data1[] = ['saloon_id' =>$saloon_id, 'service_name' => $total_service[$j], 'service_price' => $total_price[$j],'other' => $total_other[$j]];
            }
            DB::table('saloons_services')->insert($data1);

        //for loop to insert data in  saloon slots
             for($z = 0; $z < count($array_of_time) - 1; $z++)
            {
             // $new_array_of_time[] = '' . $array_of_time[$z] . ' - ' . $array_of_time[$z + 1];
             $data2[]=['saloon_id'=>$saloon_id,'slot_from'=>$array_of_time[$z],'slot_to'=>$array_of_time[$z + 1],'total_seats'=>$total_seats];
            }
            DB::table('saloon_slots')->insert($data2);
             $saloon_code = ($saloon_id<10) ? 'V00'.$saloon_id : ( ($saloon_id >=10 && $saloon_id < 100) ? 'V0'.$saloon_id : 'V'.$saloon_id);
                DB::table('saloons')->where('saloon_id',$saloon_id)->update(['saloon_code'=>$saloon_code, 'saloon_status'=>0]);
              return $this->CommonController->successResponse(null,' Data Saved succesfuly',200);
     }
 }


  public function validateVendorUp($request)
    {
       $messages = [
        'required' => 'The :attribute field is required.',
        'saloon_feature_id.required' => 'The Availability field is required.'];
        return Validator::make($request->all(), [
             'owner_name' => ['required', 'string', 'min:2','regex:/^[a-zA-Z ]*$/'],
            'owner_email' => ['required','max:255','email'],
            'owner_mobile' => ['required', 'string', 'max:255','regex:/^([987]{1})(\d{1})(\d{8})$/','max:10','min:10'],
            // 'owner_pan_number' => ['string', 'max:10','min:10'],
            // 'owner_bank_name' => [ 'string', 'max:255'],
            // 'owner_IFSC_code' => ['string','max:11' ,'min:11'],
            // 'owner_account_number' => ['string', 'max:255'],
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

     public function vendor_update(Request $request,$saloon_id)
     {

        $validator = $this->validateVendorUp($request);
        if($validator->fails())
        {
            return $this->CommonController->errorResponse($validator->messages(), 200);
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
            $array_of_time[] = date ("h:i A", $start_time);
            $start_time += $add_mins;
          }

         $owner_image = $request->file('owner_image');
          if($owner_image == '')
          {
          DB::table('saloon_slots')->where('saloon_id', $saloon_id)->update(['status'=>'old']);
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
                             'saloon_pincode'=>$request->saloon_pincode,
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
             $data2[]=['saloon_id'=>$saloon_id,'slot_from'=>$array_of_time[$z],'slot_to'=>$array_of_time[$z + 1],'total_seats'=>$total_seats,'status'=>'new'];
            }
            DB::table('saloon_slots')->insert($data2);
      }
      else{
          // $validatedData = $request->validate(['EngineerDocuments' => ['mimes:pdf','max:10240'] ]);
            DB::table('saloon_slots')->where('saloon_id', $saloon_id)->update(['status'=>'old']);
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
             $data2[]=['saloon_id'=>$saloon_id,'slot_from'=>$array_of_time[$z],'slot_to'=>$array_of_time[$z + 1],'total_seats'=>$total_seats,'status'=>'new'];
            }
            DB::table('saloon_slots')->insert($data2);
      }
       return $this->CommonController->successResponse(null,'Data updated succesfuly',200);

     }
 }

     public function destroy($saloon_id)
    {
        $this->middleware('permission');
        $saloon_status=1;
       DB::table('saloons')->where('saloon_id',$saloon_id)->update(['saloon_status'=>$saloon_status]);
       Session::flash('message', 'Your DATA Deleted Successfully');
      return redirect()->action('SaloonController@index');
    }


    public function saloon_working_days(Request $request)
    {
          $days_list=DB::table('days')->get();
          return $this->CommonController->successResponse($days_list,' Data Fetched succesfuly',200);
    }

    public function update_saloon_status(Request $request)
    {
       DB::table('saloons')->where('saloon_id',$request->saloon_id)
                           ->update(['admin_approval'=>$request->admin_approval,
                                     'deboard_reason'=>$request->deboard_reason
                                     ]);
       Session::flash('message', 'Saloon Deboard Successfully');
      return redirect()->action('SaloonController@index');
    }
    public function update_saloon_commission_rate(Request $request,$saloon_id)
    {
      // dd($saloon_id);
       DB::table('saloons')->where('saloon_id',$saloon_id)
                           ->update(['commission_rate'=>$request->rate
                                                 ]);
       Session::flash('message', 'Saloon Deboard Successfully');
      return redirect()->action('SaloonController@index');
    }
}
