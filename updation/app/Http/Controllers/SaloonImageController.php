<?php

namespace App\Http\Controllers;

use App\SaloonImageModel;
use Illuminate\Http\Request;
use  App\Http\Controllers\CommonController;
use DB; 
use File;
use Validator;

class SaloonImageController extends Controller
{

    public function __construct(Request $request)
    {
     $this->CommonController=new CommonController();
    }

    public function index()
    {  
      // $images_list=SaloonImageModel::all();
      // return view('saloon_image.index',compact('images_list')); 
    }

    public function all_images(Request $request,$saloon_id,$web=Null)
    {
      // dd($request->Source);
       $images=DB::table('saloons_images') ->where('saloon_id', $saloon_id)->get(); 
       if($web)
       {
        return view('saloon_image.index',compact('images','saloon_id'));
       }
       else
       {
         return $this->CommonController->successResponse($images,'Data fetched succesfuly',200);
       }
    }

    public function add_images($saloon_id)
    {

        // $images=DB::table('saloons_images')->where('saloon_id', $saloon_id)->get();
         return view('saloon_image.add_saloon_image',compact('saloon_id'));
    }
   
    public function validateImges($request)
    {
        return Validator::make($request->all(), [
             'saloon_id' => ['required', 'string'],
             'saloon_image'=>['required']                           
            ]);
    }


    public function store(Request $request)
    {
       
        $validatedData = $request->validate(['saloon_image' => 'required' ]);

        if($request->Source=='Web')
        {
           for($i = 0; $i < count($request->file('saloon_image')); $i++)
        {    
            $saloon_id=$request->saloon_id;
            $saloon_image = $this->CommonController->upload_image($request->file('saloon_image')[$i],"saloon_image");
            $data[] = ['saloon_id' => $saloon_id, 'saloon_image' => $saloon_image];
        }
        DB::table('saloons_images')->insert($data);
        return redirect('Saloon-Images/'.$saloon_id.'/all-images/Web');
      }
      else{
  
          $validator = $this->validateImges($request);

        if($validator->fails())
        {
            return $this->CommonController->errorResponse($validator->messages(), 422);
        }
        else
        {        
         for($i = 0; $i < count($request->file('saloon_image')); $i++)
        {    
            $saloon_id=$request->saloon_id;
            $saloon_image = $this->CommonController->upload_image($request->file('saloon_image')[$i],"saloon_image");
            $data[] = ['saloon_id' => $saloon_id, 'saloon_image' => $saloon_image];
        }
        DB::table('saloons_images')->insert($data);
         return $this->CommonController->successResponse(null,' Data Saved succesfuly',200);
      }
    }

    }

    public function show(SaloonImageModel $saloonImageModel)
    {
        //
    }

    public function edit(SaloonImageModel $saloonImageModel)
    {
        //
    }

    public function update(Request $request, SaloonImageModel $saloonImageModel)
    {
        //
    }

    public function destroy(Request $request,$saloon_image_id)
    {
       $saloon_id=$request->saloon_id;
       $image=SaloonImageModel::where('saloon_image_id',$saloon_image_id)->get()->toArray();
       foreach ($image as $img_delete) {
        //dd($img_delete['saloon_image']);
        unlink(public_path('images/saloon_image/'. $img_delete['saloon_image']));
        }
       DB::table('saloons_images')->where('saloon_image_id', $saloon_image_id)->delete();

       if($request->Source=='Web')
       {
         return redirect('Saloon-Images/'.$saloon_id.'/all-images/Web');
       }
       else
       {
        return $this->CommonController->successResponse(null,' Data deleted succesfuly',200);
       }
    }
}
