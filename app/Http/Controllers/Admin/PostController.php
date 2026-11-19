<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\Post\PostRequest;
use App\Models\Post\Post;
use App\Models\Post\PostMeta;
use App\Models\Subject;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    /**
     * Map admin URL prefix → post_type / labels.
     *
     * @return array{label:string,type:string,url:string}
     */
    private function contentTypeConfig()
    {
        $types = [
            'posts' => ['Post', 'post', 'posts'],
            'pages' => ['Page', 'page', 'pages'],
            'sliders' => ['Slider', 'slider', 'sliders'],
            'universities' => ['University', 'university', 'universities'],
            'success-story' => ['Success Story', 'success-story', 'success-story'],
            'events' => ['Event', 'event', 'events'],
            'testimonials' => ['Testimonial', 'testimonial', 'testimonials'],
            'scholarships' => ['Scholarship', 'scholarship', 'scholarships'],
            'services' => ['Service', 'service', 'services'],
            'videos' => ['Video', 'video', 'videos'],
        ];

        foreach ($types as $path => $cfg) {
            if (request()->is($path) || request()->is($path . '/*')) {
                return [
                    'label' => $cfg[0],
                    'type' => $cfg[1],
                    'url' => $cfg[2],
                ];
            }
        }

        return [
            'label' => 'Post',
            'type' => 'post',
            'url' => 'posts',
        ];
    }

    public function index(Request $request)
    {
        $cfg = $this->contentTypeConfig();
        $posts = Post::orderBy('id', 'DESC');
        $data['request'] = $request;

        if ($request->input('subject')) {
            $posts->whereHas('subjects', function ($q) use ($request) {
                $q->where('id', $request->input('subject'));
            });
        }

        if ($request->input('search')) {
            $posts->where(function ($q) use ($request) {
                $q->where('post_title', 'like', '%' . $request->input('search') . '%')
                    ->orWhere('post_name', 'like', '%' . $request->input('search') . '%')
                    ->orWhere('post_content', 'like', '%' . $request->input('search') . '%');
            });
        }

        $posts->where('post_type', $cfg['type']);
        $data['title'] = $cfg['label'] . ' List';
        $data['type'] = $cfg['type'];
        $data['url'] = $cfg['url'];

        if ($cfg['type'] === 'university') {
            $data['subjects'] = Subject::all();
        }

        $data['posts'] = $posts->with(['user', 'categories', 'subjects', 'postMetas'])->paginate(20);

        return view('admin.post.index')->with($data);
    }

    public function create()
    {
        $cfg = $this->contentTypeConfig();
        $data['title'] = $cfg['label'] . ' Create';
        $data['type'] = $cfg['type'];
        $data['url'] = $cfg['url'];

        if ($cfg['type'] === 'university') {
            $data['subjects'] = Subject::orderBy('name', 'ASC')->get();
        }

        $data['tags'] = Tag::orderBy('name', 'ASC')->get();
        $data['categories'] = Category::orderBy('name', 'ASC')->get();

        return view('admin.post.create')->with($data);
    }

    public function store(PostRequest $request)
    {
        DB::beginTransaction();
        try {
            $post = new Post();
            $postFillableData = $request->only($post->getFillable());
            $post->fill($postFillableData)->save();
            $post->post_author = Auth::user()->id;

            if ($request->filled('post_excerpt')) {
                $post->post_excerpt = $request->input('post_excerpt');
            }

            if ($request->file('image')) {
                $image = $post->id . '_' . time() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/posts'), $image);
                $post->image = 'uploads/posts/' . $image;
            }
            $post->save();

            if ($request->post_type == 'university') {
                $post->subjects()->attach($request->subjects);
            }
            $post->tags()->attach($request->tags);
            $post->categories()->attach($request->categories);

            if ($request->has('meta')) {
                $data = collect($request->meta)->map(function ($value, $key) use ($post) {
                    return [
                        'post_id' => $post->id,
                        'meta_key' => $key,
                        'meta_value' => $value,
                    ];
                })->values()->toArray();
                PostMeta::insert($data);
            }

            DB::commit();
            \App\Support\PublicContent::forgetCaches();
            return redirect()->back()->with('success', 'Successfully Added');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Operation Failed: ' . $th->getMessage());
        }
    }

    public function show($id)
    {
        $cfg = $this->contentTypeConfig();
        $data['post'] = Post::findOrFail($id);
        $data['title'] = $cfg['label'] . ' View';
        $data['type'] = $cfg['type'];
        $data['url'] = $cfg['url'];

        return view('admin.post.show')->with($data);
    }

    public function edit($id)
    {
        $cfg = $this->contentTypeConfig();
        $data['post'] = Post::with('postMetas')->findOrFail($id);
        $data['title'] = $cfg['label'] . ' Edit';
        $data['type'] = $cfg['type'];
        $data['url'] = $cfg['url'];

        if ($cfg['type'] === 'university') {
            $data['subjects'] = Subject::orderBy('name', 'ASC')->get();
        }

        $data['tags'] = Tag::orderBy('name', 'ASC')->get();
        $data['categories'] = Category::orderBy('name', 'ASC')->get();

        return view('admin.post.edit')->with($data);
    }

    public function update(PostRequest $request, $id)
    {
        $post = Post::findOrFail($id);
        DB::beginTransaction();
        try {
            $postFillableData = $request->only($post->getFillable());
            $post->fill($postFillableData)->save();
            $post->post_author = Auth::user()->id;

            if ($request->filled('post_excerpt')) {
                $post->post_excerpt = $request->input('post_excerpt');
            }

            if ($request->file('image')) {
                $image = $post->id . '_' . time() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/posts'), $image);
                $post->image = 'uploads/posts/' . $image;
            }
            $post->save();

            if ($request->post_type == 'university') {
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
            \App\Support\PublicContent::forgetCaches();
            return redirect()->back()->with('success', 'Successfully Updated');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Operation Failed: ' . $th->getMessage());
        }
    }

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
            \App\Support\PublicContent::forgetCaches();
            return redirect()->back()->with('success', 'Successfully Deleted');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Operation Failed: ' . $th->getMessage());
        }
    }

    public function exportSeeder()
    {
        $posts = DB::table('posts')->get()->toArray();
        $postsArray = array_map(function ($post) {
            return (array) $post;
        }, $posts);

        $seederContent = "<?php\n\nnamespace Database\\Seeders;\n\nuse Illuminate\\Database\\Seeder;\nuse Illuminate\\Support\\Facades\\DB;\n\nclass PostsTableSeeder extends Seeder\n{\n    public function run()\n    {\n        DB::table('posts')->insert(" . var_export($postsArray, true) . ");\n    }\n}\n";

        $filePath = database_path('seeders/PostsTableSeeder.php');
        File::put($filePath, $seederContent);

        return 'Seeder created at: ' . $filePath;
    }
}
