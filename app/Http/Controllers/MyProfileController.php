<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class MyProfileController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;
        $userManagement = User::findOrFail($user_id);
        $page_title = "My Profile";
        return view('myprofile.show', compact('userManagement', 'page_title'));
    }
    public function basicEdit()
    {
        $user_id = Auth::user()->id;
        $data['userManagement'] = User::findOrFail($user_id);
        $data['page_title'] = 'Update Your Profile';
        return view('myprofile.edit')->with($data);
    }
    public function basicUpdate(Request $request)
    {
        $user_id = Auth::user()->id;
        $request->validate(
            [
                'name' => 'required',
                'email' => $rules['email'] = 'required|email|unique:users,email,' . $user_id,
                'mobile_no' => $rules['mobile_no'] = 'required|unique:users,mobile_no,' . $user_id,
            ],
        );
        DB::beginTransaction();
        try {
            $user = User::findOrFail($user_id);
            $input = $request->all();
            $user->fill($input)->save();
            DB::commit();
            return redirect()->route('my-profile.index')->with('success', 'Successfully Updated');
        } catch (\Throwable $th) {
            $th->getMessage();
            DB::rollback();
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function imageUpload()
    {
        $user_id = Auth::user()->id;
        $data['userManagement'] = User::findOrFail($user_id);
        $data['page_title'] = "Update Profile Picture";
        return view('myprofile.imageUpload')->with($data);
    }

    public function imageUpdate(Request $request)
    {
        $user_id = Auth::user()->id;
        $request->validate([
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $user = User::findOrFail($user_id);
            if ($file = $request->file('image')) {
                $profilePic = $user_id . '_' . time() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/profile'), $profilePic);
            } else {
                $profilePic = NULL;
            }
            $user->profile_pic = $profilePic;
            $user->save();
            DB::commit();
            return redirect()->route('my-profile.index')->with('success', 'Successfully Updated');
        } catch (\Throwable $th) {
            $th->getMessage();
            DB::rollback();
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function changePassword()
    {
        $data['page_title'] = 'Password Change';
        return view('myprofile.changePassword')->with($data);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);
        User::find(auth()->user()->id)->update(['password' => $request->new_password]);
        return redirect()->route('my-profile.index')->with('success', 'Password change successfully.');
    }
}
