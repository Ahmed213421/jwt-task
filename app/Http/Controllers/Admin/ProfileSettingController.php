<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.auth.profileSetting');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'string|max:255',
            'email' => 'email|max:255|unique:admins,email,' . auth()->id(),
            'password' => 'string|min:8|confirmed|nullable',
            'photo' => 'image',
        ]);
        if ($validator->fails()) {
            // Redirect back to the form with the error messages
            return back()
            ->withErrors($validator)
            ->withInput();
        }



        $user = Admin::find(Auth::guard('admin')->user()->id);
        $user->name = $request->name;
        $user->email = $request->email;


        if ($request->hasFile('photo')) {
            if($user->image){
                if (file_exists(public_path($user->image->imagepath))) {
                    unlink(public_path($user->image->imagepath));
                }
                $path = 'dashboard/'.$request->photo->storeAs('admin_profile', time().'_'.$request->photo->getClientOriginalName(),'images');

                $user->image->update(['imagepath' => $path]);

            }
            else{
                $path = 'dashboard/'.$request->photo->storeAs('admin_profile', time().'_'.$request->photo->getClientOriginalName(),'images');

                $user->image()->create(['imagepath' => $path]);
            }
        }

        if(!Hash::check($request->oldpassword, $user->password)){
            return back()->withErrors(['oldpassword' => 'The old password is incorrect.']);
        }

        if ($request->filled('password') && Hash::check($request->oldpassword, $user->password)) {
            $user->password = Hash::make($request->password);
            $user->save();


            Auth::guard('admin')->logout();


            return redirect()->route('admin.logout');
        }

        $user->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
