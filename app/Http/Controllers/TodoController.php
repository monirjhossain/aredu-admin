<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserRequest;
use App\Http\Resources\TodoCollection;
use App\Http\Resources\UserCollection;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TodoController extends Controller
{
    public function index()
    {
        $data['todoLists'] = TodoCollection::collection(Todo::where('user_id', Auth::user()->id)->orderby('id', 'DESC')->paginate(50));
        $data['page_title'] = 'Todo List';
        return view('todo.index')->with($data);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        DB::beginTransaction();
        try {
            $todo = Todo::create($data);
            DB::commit();
            return redirect()->route('todo.index')->with('success', 'Successfully Added');
        } catch (\Throwable $th) {
            return $th->getMessage();
            DB::rollback();
            //throw $th;
            return redirect()->back()->with('error', 'Operation Failed.');;
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        DB::beginTransaction();
        try {
            $todo = Todo::findOrFail($id);
            $input = $request->all();
            $todo->fill($input)->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Successfully Updated'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Oparation Failed'
            ]);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $todo = Todo::findOrFail($id);
            $todo->delete();
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
    }
}
