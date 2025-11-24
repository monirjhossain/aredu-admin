<?php

namespace App\Http\Controllers\post;

use App\Http\Requests\User\UserRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostRequest;
use App\Http\Resources\PostCollection;
use App\Models\Post\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index()
    {
        $data['users'] = PostCollection::collection(User::orderby('id', 'DESC')->paginate(20));
        $data['page_title'] = 'Post List';
        return view('post.index')->with($data);
    }

    public function create()
    {
        $data['page_title'] = 'Add New Post';
        return view('post.add')->with($data);
    }

    public function store(PostRequest $request)
    {
        return $request;
        DB::beginTransaction();
        try {
            $post = new Post();
            $postFillableData = $request->only($post->getFillable());
            $post->fill($postFillableData)->save();
            $post->post_author = Auth::user()->id;
            $post->post_type = 'post';
            $post->image = 'image';
            $post->save();
            DB::commit();
            return $this->respondCreated(trans('message.saved_successfully'), [
                'post' => $post,
            ]);

            $user = User::create($request->all());
            if ($request->file('pro_pic')) {
                $profilePic = $user->id . '_' . time() . '.' . $request->pro_pic->extension();
                $request->pro_pic->move(public_path('uploads/profile'), $profilePic);
                $user->profile_pic = $profilePic;
                $user->save();
            }
            DB::commit();
            return redirect()->route('user-management.index')->with('success', 'Successfully Added');
        } catch (\Throwable $th) {
            // $th->getMessage();
            DB::rollback();
            //throw $th;
            return redirect()->back()->with('error', 'Operation Failed.');;
        }
    }

    public function show($id)
    {
        $data['userManagement'] = User::findOrFail($id);
        $data['page_title'] = 'User Details';
        return view('post.show')->with($data);
    }

    public function edit($id)
    {
        $data['userManagement'] = User::findOrFail($id);
        $data['page_title'] = 'Edit User';
        return view('post.edit')->with($data);
    }

    public function update(UserRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $input = $request->all();
            $user->fill($input)->save();
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
