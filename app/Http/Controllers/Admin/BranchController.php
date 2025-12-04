<?php

namespace App\Http\Controllers\Admin;


use App\Http\Requests\User\UserRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Account\Wallet;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::orderby('id', 'ASC');
        $data['branches'] = UserCollection::collection($branches->paginate(20));
        $data['page_title'] = 'Branch List';
        return view('admin.branch.index')->with($data);
    }

    public function create()
    {
        $data['page_title'] = 'Add New Branch';
        return view('admin.branch.add')->with($data);
    }

    public function store(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            $branch = Branch::create($request->all());
            if ($request->file('pro_pic')) {
                $profilePic = $branch->id . '_' . time() . '.' . $request->pro_pic->extension();
                $request->pro_pic->move(public_path('media/custom'), $profilePic);
                $branch->logo = $profilePic;
                $branch->save();
            }
            // if( $branch->role_id == 4 ){
            //     $wallets = [
            //         ['user_id' => null, 'name' => 'Cash', 'currency_id' => 1, 'balance' => 0, 'branch_id' => $branch->branch], // BDT
            //         ['user_id' => null, 'name' => 'Bank', 'currency_id' => 1, 'balance' => 0, 'branch_id' => $branch->branch], // BDT
            //         ['user_id' => null, 'name' => 'Bkash', 'currency_id' => 1, 'balance' => 0, 'branch_id' => $branch->branch], // BDT
            //         ['user_id' => null, 'name' => 'Nagad', 'currency_id' => 1, 'balance' => 0, 'branch_id' => $branch->branch], // BDT
            //     ];

            //     foreach ($wallets as $wallet) {
            //         Wallet::create($wallet);
            //     }
            // }
            DB::commit();
            return redirect()->route('branches.index')->with('success', 'Successfully Added');
        } catch (\Throwable $th) {
            // return $th->getMessage();
            DB::rollback();
            //throw $th;
            return redirect()->back()->with('error', 'Operation Failed.');;
        }
    }

    public function show($id)
    {
        $data['userManagement'] = Branch::findOrFail($id);
        $data['page_title'] = 'Branch Details';
        return view('admin.branch.show')->with($data);
    }

    public function edit($id)
    {
        $data['user'] = Branch::findOrFail($id);
        $data['page_title'] = 'Edit Branch';
        return view('admin.branch.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        // return $request;
        DB::beginTransaction();
        try {
            $branch = Branch::findOrFail($id);
            $input = $request->all();
            $branch->fill($input)->save();
            if ($request->file('pro_pic')) {
                $profilePic = $branch->id . '_' . time() . '.' . $request->pro_pic->extension();
                $request->pro_pic->move(public_path('media/custom'), $profilePic);
                $branch->logo = $profilePic;
                $branch->save();
            }
            DB::commit();
            return redirect()->route('branches.index')->with('success', 'Successfully Updated');
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
        // $user = User::findOrFail($id);
        // $user->delete();
        return redirect()->route('branches.index')->with('success', 'Deleted successfully');
    }
}
