<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\Category;
use App\District;
use App\Division;
use App\Http\Requests\Post\PostRequest;
use App\Models\Post\Post;
use App\Models\Tag;
use App\Upazila;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $post_type = $request->input('post_type');
        $posts = Post::orderBy('id', 'DESC');
        if($post_type){
            $posts->where('post_type', $post_type);
        }
        $data['posts'] = $posts->with('categories', 'tags')->paginate(20);
        return $this->sendResponse($data, 'successfully.');
        // return view('admin.post.index', compact('posts'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::all();
        $categories = Category::all();
        return view('admin.post.create', compact(['categories', 'tags']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        DB::beginTransaction();
        try {
            $post = new Post();
            $postFillableData = $request->only($post->getFillable());
            $post->fill($postFillableData)->save();
            $post->post_author = Auth::user()->id;
            if ($request->file('image')) {
                $image = $post->id . '_' . time() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/posts'), $image);
                $post->image = 'uploads/posts/' . $image;
            }
            $post->save();
            $post->tags()->attach($request->tags);
            $post->categories()->attach($request->categories);
            DB::commit();
            Session::flash('success', 'Post created successfully');
            return redirect()->back()->with('success', 'Successfully Added');
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
            return redirect()->back()->with('error', 'Operation Failed.');;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['post'] = Post::findOrfail($id);
        return $this->sendResponse($data, 'successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $tags = Tag::all();
        $categories = Category::all();
        return view('admin.post.edit', compact(['post', 'categories', 'tags']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
        DB::beginTransaction();
        try {
            // $post = new Post();
            $postFillableData = $request->only($post->getFillable());
            $post->fill($postFillableData)->save();
            $post->post_author = Auth::user()->id;
            if ($request->file('image')) {
                $image = $post->id . '_' . time() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/posts'), $image);
                $post->image = 'uploads/posts/' . $image;
            }
            $post->save();
            $post->tags()->sync($request->tags);
            $post->categories()->sync($request->categories);
            DB::commit();
            return redirect()->back()->with('success', 'Successfully Added');
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
            return redirect()->back()->with('error', 'Operation Failed.');;
        }

        if ($request->hasFile('image')) {
            if ($post->image != null) {
                Storage::disk('public')->delete($post->image);
            }
            $image = $request->image;
            $image_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/post/', $image_new_name);
            $post->image = '/storage/post/' . $image_new_name;
        }
        $post->save();

        Session::flash('success', 'Post updated successfully');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if ($post) {
            if (file_exists(public_path($post->image))) {
                unlink(public_path($post->image));
            }

            $post->delete();
            Session::flash('Post deleted successfully');
        }

        return redirect()->back();
    }

    public function upazilas(Request $request)
    {

        if ($request->ajax()) {
            if ($request->id == null) {
                return response()->json([
                    'upazilas' => Upazila::all()
                ]);
            }
            return response()->json([
                'upazilas' => Upazila::where('district_id', $request->id)->get()
            ]);
        }
    }
}
