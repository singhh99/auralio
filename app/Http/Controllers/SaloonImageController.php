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
       $this->middleware('permission', ['only' => ['add_images','all_images_web','delete_images']]);
    }

    public function index()
    {
      // $images_list=SaloonImageModel::all();
      // return view('saloon_image.index',compact('images_list'));
    }

    public function all_images(Request $request,$saloon_id,$web=Null)
    {
      // dd($request->Source);
       // $images=DB::table('saloons_images') ->where('saloon_id', $saloon_id)->get();
       if($web)
       {
         $this->middleware('permission');
        $images=DB::table('saloons_images') ->where('saloon_id', $saloon_id)->get();
        return view('saloon_image.index',compact('images','saloon_id'));
       }
       else
       {
        $images=DB::table('saloons_images') ->where('saloon_id', $saloon_id)->get();
         return $this->CommonController->successResponse($images,'Data fetched succesfuly',200);
       }
    }

    public function add_images($saloon_id)
    {
         $this->middleware('permission');
         return view('saloon_image.add_saloon_image',compact('saloon_id'));
    }

    public function validateImges($request)
    {
        return Validator::make($request->all(), [
             'saloon_id' => ['required', 'string'],
             'saloon_image'=>['required','max:10240']
            ]);
    }


    public function store(Request $request)
    {
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


    public function destroy(Request $request,$saloon_image_id)
    {

        $saloon_id=$request->saloon_id;
        $image=SaloonImageModel::where('saloon_image_id',$saloon_image_id)->get();
        $file_path = public_path('/images/saloon_image/'. $image[0]->saloon_image);
        if(File::exists($file_path))
        {
          //dd($file_path);
          File::delete($file_path);
        }
        DB::table('saloons_images')->where('saloon_image_id', $saloon_image_id)->delete();

        return $this->CommonController->successResponse(null,' Data deleted succesfuly',200);
    }

 // delete function saloon for web
    public function delete_images(Request $request,$saloon_image_id)
    {
      
        $saloon_id=$request->saloon_id;
        $image=SaloonImageModel::where('saloon_image_id',$saloon_image_id)->get();
        $file_path = public_path('/images/saloon_image/'. $image[0]->saloon_image);
        if(File::exists($file_path))
        {
          File::delete($file_path);
        }
        DB::table('saloons_images')->where('saloon_image_id', $saloon_image_id)->delete();

         return redirect('Saloon-Images/'.$saloon_id.'/all-images/Web');
    }
   //function to  add images from web
    public function add_images_web(Request $request)
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

    // function to view all salon images  for web

     public function all_images_web(Request $request,$saloon_id)
     {

        $this->middleware('permission');
        $images=DB::table('saloons_images') ->where('saloon_id', $saloon_id)->get();
        return view('saloon_image.index',compact('images','saloon_id'));

     }



  }
