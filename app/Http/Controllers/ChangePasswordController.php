<?php

namespace App\Http\Controllers;
use Session;
use Illuminate\Http\Request;
use Auth;
use Hash;

class ChangePasswordController extends Controller
{
    
    public function index()
    {
        return view('auth.change_password');
    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        $new_password=$request->password;
        $old_password=$request->user_password;

        $user = auth()->user();
        //$this->validator($request->all())->validate();
        if (Hash::check($old_password, $user->password)) 
        {
            $user->password = Hash::make($request->get('password'));
            $user->save();
            Session::flash('message', 'Your Password Successfully Changed');
            return redirect()->action('ChangePasswordController@index');
        } 
        else 
        {
            Session::flash('message', 'Current password is incorrect');
            return redirect()->action('ChangePasswordController@index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
