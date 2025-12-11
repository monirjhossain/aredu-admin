<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserRequest;
use App\Http\Resources\UserCollection;
use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Account\Wallet;

class UserController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();
        $id = 0;
        $users = User::orderby('id', 'DESC');
        if($currentUser->branch != 1){ //admin
            $id = $currentUser->branch;
            $users->where('branch', $currentUser->branch);
        }
        $data['users'] = UserCollection::collection($users->paginate(20));
        $data['page_title'] = 'User List';
        return view('user.index')->with($data);
    }

    public function create()
    {
        $currentUser = Auth::user();
        $roles = Role::orderby('id', 'ASC')->where('id', '!=', 4);
        $users = User::orderby('id', 'ASC');
        $branches = Branch::orderby('id', 'ASC');

        if($currentUser->branch != 1){ //Without Dhaka
            $branches->where('id', $currentUser->branch);
            $roles->where('id', 8); //Only Counselor
        }
        $roles->where('id', '>' , $currentUser->role_id);
        $users->where('branch', $currentUser->branch);
        $data['roles'] = $roles->get();
        $data['users'] = $users->get();
        $data['branches'] = $branches->get();
        $data['page_title'] = 'Add New User';
        return view('user.add')->with($data);
    }

    public function store(UserRequest $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            $user = User::create($request->all());
            $user->related_to = $request->related_to == null || $request->related_to == '' ? Auth::id() : $request->related_to;
            $user->save();
            if ($request->file('pro_pic')) {
                $profilePic = $user->id . '_' . time() . '.' . $request->pro_pic->extension();
                $request->pro_pic->move(public_path('uploads/profile'), $profilePic);
                $user->profile_pic = $profilePic;
                $user->save();
            }
            DB::commit();
            return redirect()->route('user-management.index')->with('success', 'Successfully Added');
        } catch (\Throwable $th) {
            // return $th->getMessage();
            DB::rollback();
            //throw $th;
            return redirect()->back()->with('error', 'Operation Failed.');;
        }
    }

    public function show($id)
    {
        $data['userManagement'] = User::findOrFail($id);
        $data['page_title'] = 'User Details';
        return view('user.show')->with($data);
    }

    public function edit($id)
    {
        $data['user'] = User::findOrFail($id);
        $currentUser = User::findOrFail(Auth::id());
        $roles = Role::orderby('id', 'ASC')->where('id', '!=', 4);
        $users = User::orderby('id', 'ASC');
        $branches = Branch::orderby('id', 'ASC');

        // if($currentUser->role_id == 2){
        $roles->where('id', '>' , $currentUser->role_id);
        $users->where('related_to', [$currentUser->id])->orWhere('id', [$currentUser->id]);
        // }
        $data['roles'] = $roles->get();
        $data['users'] = $users->get();
        $data['branches'] = $branches->get();
        $data['page_title'] = 'Edit User';
        // return $data;
        return view('user.edit')->with($data);
    }

    public function update(UserRequest $request, $id)
    {
        // return $request;
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $input = $request->all();
            $barchManager = false;
            if ($user->role_id == 4) {
                $barchManager = true;
            }
            $user->fill($input)->save();
            if ($barchManager) {
                $user->role_id = 4;
                $user->save();
            }
            if ($request->file('pro_pic')) {
                $profilePic = $user->id . '_' . time() . '.' . $request->pro_pic->extension();
                $request->pro_pic->move(public_path('uploads/profile'), $profilePic);
                $user->profile_pic = $profilePic;
                $user->save();
            }
            DB::commit();
            return redirect()->route('user-management.index')->with('success', 'Successfully Updated');
        } catch (\Throwable $th) {
            return $th->getMessage();
            DB::rollback();
            //throw $th;
            return redirect()->back();
        }
    }

    public function destroy(Request $request, $id)
    {
        // return $id;
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('user-management.index')->with('success', 'Deleted successfully');
    }
}
