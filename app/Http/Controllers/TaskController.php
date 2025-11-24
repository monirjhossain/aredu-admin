<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserRequest;
use App\Http\Resources\TodoCollection;
use App\Http\Resources\UserCollection;
use App\Models\Task;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    public function index()
    {
        $data['todoLists'] = TodoCollection::collection(Todo::orderby('id', 'DESC')->paginate(50));
        $data['page_title'] = 'Todo List';
        return view('todo.index')->with($data);
    }

    public function create()
    {
        $data['page_title'] = 'Add New User';
        return view('todo.add')->with($data);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        DB::beginTransaction();
        try {
            $task = task::create($data);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Successfully Create',
                'task' => $task,
            ]);
        } catch (\Throwable $th) {
            return $th->getMessage();
            DB::rollback();
            //throw $th;
            return redirect()->back();
        }
    }

    public function show($id)
    {
        $data['userManagement'] = User::findOrFail($id);
        $data['page_title'] = 'User Details';
        return view('todo.show')->with($data);
    }

    public function edit($id)
    {
        $data['userManagement'] = User::findOrFail($id);
        $data['page_title'] = 'Edit User';
        return view('todo.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        DB::beginTransaction();
        try {
            $todo = Task::findOrFail($id);
            $input = $request->all();
            $todo->fill($input)->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Successfully Updated'
            ]);
        } catch (\Throwable $th) {
            return $th->getMessage();
            DB::rollback();
            //throw $th;
            return redirect()->back();
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();
            return response()->json([
                'success' => true,
                'message' => 'Deleted successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Oparation Failed'
            ]);
        }
        return redirect()->route('user-management.index')->with('success', 'Deleted successfully');
    }
}
