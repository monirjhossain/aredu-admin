<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\Category;
use App\Http\Requests\Post\PostRequest;
use App\Models\Post\Post;
use App\Models\Post\PostMeta;
use App\Models\Subject;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::orderBy('id', 'DESC');
        $data['request'] = $request;

        if ($request->input('subject')) {
            $posts->whereHas('subjects', function ($q) use ($request) {
                $q->where('id', $request->input('subject'));
            });
        }

        if ($request->input('search')) {
            $posts->where('post_title', 'like', '%' . $request->input('search') . '%')
                ->orWhere('post_name', 'like', '%' . $request->input('search') . '%')
                ->orWhere('post_content', 'like', '%' . $request->input('search') . '%');
        }

        if (request()->is('posts') || request()->is('posts/*')) {
            $posts->where('post_type', 'post');
            $data['title'] = 'Post List';
            $data['type'] = 'post';
            $data['url'] = 'posts';
        }
        if (request()->is('pages') || request()->is('pages/*')) {
            $posts->where('post_type', 'page');
            $data['title'] = 'Page List';
            $data['type'] = 'page';
            $data['url'] = 'pages';
        }
        if (request()->is('sliders') || request()->is('sliders/*')) {
            $posts->where('post_type', 'slider');
            $data['title'] = 'Slider List';
            $data['type'] = 'slider';
            $data['url'] = 'sliders';
        }
        if (request()->is('universities') || request()->is('universities/*')) {
            $posts->where('post_type', 'university');
            $data['title'] = 'University List';
            $data['type'] = 'university';
            $data['url'] = 'universities';
            $data['subjects'] = Subject::all();
        }
        if (request()->is('success-story') || request()->is('success-story/*')) {
            $posts->where('post_type', 'success-story');
            $data['title'] = 'success-story List';
            $data['type'] = 'success-story';
            $data['url'] = 'success-story';
        }
        if (request()->is('events') || request()->is('events/*')) {
            $posts->where('post_type', 'event');
            $data['title'] = 'event List';
            $data['type'] = 'event';
            $data['url'] = 'events';
        }
        if (request()->is('testimonials') || request()->is('testimonials/*')) {
            $posts->where('post_type', 'testimonial');
            $data['title'] = 'testimonial List';
            $data['type'] = 'testimonial';
            $data['url'] = 'testimonials';
        }

        $data['posts'] = $posts->with(['user', 'categories', 'subjects', 'postMetas'])->paginate(20);

        return view('admin.post.index')->with($data);
    }

    public function create()
    {
        if (request()->is('posts') || request()->is('posts/*')) {
            $data['title'] = 'Post Create';
            $data['type'] = 'post';
            $data['url'] = 'posts';
        }
        if (request()->is('pages') || request()->is('pages/*')) {
            $data['title'] = 'Page Create';
            $data['type'] = 'page';
            $data['url'] = 'pages';
        }
        if (request()->is('sliders') || request()->is('sliders/*')) {
            $data['title'] = 'Slider Create';
            $data['type'] = 'slider';
            $data['url'] = 'sliders';
        }
        if (request()->is('universities') || request()->is('universities/*')) {
            $data['title'] = 'University Create';
            $data['type'] = 'university';
            $data['url'] = 'universities';
            $data['subjects'] = Subject::orderBy('name', 'ASC')->get();
        }
        if (request()->is('success-story') || request()->is('success-story/*')) {
            $data['title'] = 'success-story Create';
            $data['type'] = 'success-story';
            $data['url'] = 'success-story';
        }
        if (request()->is('events') || request()->is('events/*')) {
            $data['title'] = 'event Create';
            $data['type'] = 'event';
            $data['url'] = 'events';
        }
        if (request()->is('testimonials') || request()->is('testimonials/*')) {
            $data['title'] = 'testimonial Create';
            $data['type'] = 'testimonial';
            $data['url'] = 'testimonials';
        }
        $data['tags'] = Tag::orderBy('name', 'ASC')->get();
        $data['categories'] = Category::orderBy('name', 'ASC')->get();
        return view('admin.post.create')->with($data);
}

    public function store(PostRequest $request)
    {
        // return $request;
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
            if($request->post_type == "university"){
                $post->subjects()->attach($request->subjects);
            }
            $post->tags()->attach($request->tags);
            $post->categories()->attach($request->categories);

            if ($request->has('meta')) {
                $data = collect($request->meta)->map(function ($value, $key) use ($post) {
                    return [
                        'post_id'    => $post->id,
                        'meta_key'        => $key,
                        'meta_value'      => $value,
                    ];
                })->values()->toArray();
                PostMeta::insert($data);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Successfully Added');
        } catch (\Throwable $th) {
            // DB::rollback();
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
        $data['post'] = Post::findOrFail($id);
        if (request()->is('posts') || request()->is('posts/*')) {
            $data['title'] = 'Post View';
            $data['type'] = 'post';
            $data['url'] = 'posts';
        }
        if (request()->is('pages') || request()->is('pages/*')) {
            $data['title'] = 'Page View';
            $data['type'] = 'page';
            $data['url'] = 'pages';
        }
        if (request()->is('sliders') || request()->is('sliders/*')) {
            $data['title'] = 'Slider View';
            $data['type'] = 'slider';
            $data['url'] = 'sliders';
        }
        if (request()->is('universities') || request()->is('universities/*')) {
            $data['title'] = 'University View';
            $data['type'] = 'university';
            $data['url'] = 'universities';
        }
        if (request()->is('success-story') || request()->is('success-story/*')) {
            $data['title'] = 'success-story View';
            $data['type'] = 'success-story';
            $data['url'] = 'success-story';
        }
        if (request()->is('events') || request()->is('events/*')) {
            $data['title'] = 'event View';
            $data['type'] = 'event';
            $data['url'] = 'events';
        }
        if (request()->is('testimonials') || request()->is('testimonials/*')) {
            $data['title'] = 'testimonial View';
            $data['type'] = 'testimonial';
            $data['url'] = 'testimonials';
        }
        return view('admin.post.show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['post'] = Post::with('postMetas')->findOrFail($id);
        // $post = Post::with('metas')->findOrFail($id);
        if (request()->is('posts') || request()->is('posts/*')) {
            $data['title'] = 'Post Edit';
            $data['type'] = 'post';
            $data['url'] = 'posts';
        }
        if (request()->is('pages') || request()->is('pages/*')) {
            $data['title'] = 'Page Edit';
            $data['type'] = 'page';
            $data['url'] = 'pages';
        }
        if (request()->is('sliders') || request()->is('sliders/*')) {
            $data['title'] = 'Slider Edit';
            $data['type'] = 'slider';
            $data['url'] = 'sliders';
        }
        if (request()->is('universities') || request()->is('universities/*')) {
            $data['title'] = 'University Edit';
            $data['type'] = 'university';
            $data['url'] = 'universities';
            $data['subjects'] = Subject::orderBy('name', 'ASC')->get();
        }
        if (request()->is('events') || request()->is('events/*')) {
            $data['title'] = 'event Edit';
            $data['type'] = 'event';
            $data['url'] = 'events';
        }
        if (request()->is('success-story') || request()->is('success-story/*')) {
            $data['title'] = 'success-story Edit';
            $data['type'] = 'success-story';
            $data['url'] = 'success-story';
        }
        if (request()->is('testimonials') || request()->is('testimonials/*')) {
            $data['title'] = 'testimonial Edit';
            $data['type'] = 'testimonial';
            $data['url'] = 'testimonials';
        }
        $data['tags'] = Tag::orderBy('name', 'ASC')->get();
        $data['categories'] = Category::orderBy('name', 'ASC')->get();
        // return $data;
        return view('admin.post.edit')->with($data);;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, $id)
    {
        // return $request;
        $post = Post::findOrFail($id);
        DB::beginTransaction();
        try {
            $postFillableData = $request->only($post->getFillable());
            $post->fill($postFillableData)->save();
            $post->post_author = Auth::user()->id;
            if ($request->file('image')) {
                $image = $post->id . '_' . time() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/posts'), $image);
                $post->image = 'uploads/posts/' . $image;
            }
            $post->save();
            if($request->post_type == "university"){
                $post->subjects()->sync($request->subjects);
            }
            $post->tags()->sync($request->tags);
            $post->categories()->sync($request->categories);

            if ($request->has('meta')) {
                foreach ($request->meta as $key => $value) {
                    $post->postMetas()->updateOrCreate(
                        ['meta_key' => $key],
                        ['meta_value' => $value]
                    );
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Successfully Added');
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
            return redirect()->back()->with('error', 'Operation Failed.');;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $post = Post::findOrFail($id);
            if ($post) {
                if (file_exists(public_path($post->image)) && $post->image != null) {
                    unlink(public_path($post->image));
                }
                $post->delete();
            }
            DB::commit();
            return redirect()->back()->with('success', 'Successfully Deleted');
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
            return redirect()->back()->with('error', 'Operation Failed.');;
        }
        return redirect()->back();
    }

    public function exportSeeder()
    {
        // 1. Get all posts data
        $posts = DB::table('posts')->get()->toArray();

        // 2. Convert objects to arrays
        $postsArray = array_map(function ($post) {
            return (array) $post;
        }, $posts);

        // 3. Seeder content
        $seederContent = "<?php\n\nnamespace Database\\Seeders;\n\nuse Illuminate\\Database\\Seeder;\nuse Illuminate\\Support\\Facades\\DB;\n\nclass PostsTableSeeder extends Seeder\n{\n    public function run()\n    {\n        DB::table('posts')->insert(" . var_export($postsArray, true) . ");\n    }\n}\n";

        // 4. Save file
        $filePath = database_path('seeders/PostsTableSeeder.php');
        File::put($filePath, $seederContent);

        return "Seeder created at: " . $filePath;
    }
}
