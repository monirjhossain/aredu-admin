<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Tag;
// use Session;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\UploadAble;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Session;

class TagController extends Controller
{
    use UploadAble;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::orderBy('created_at', 'DESC')->paginate(20);
        return view('admin.tag.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tag.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validation
        $this->validate($request, [
            'name' => 'required|string|max:255|unique:tags,name',
            // 'slug' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'image'     =>  'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // $slug = Str::slug($request->slug, '-');
        // $exetag = Tag::where('slug', $slug)->first();
        // if( $exetag != null ){
        //     Session::flash('error', 'Slug must be unique!');
        //     return redirect()->back();
        // }

        $tag = new Tag();
        $tag->name = $request->name;
        $tag->slug = preg_replace('/\s+/u', '-', trim($request->name));
        $tag->description = $request->description;

        if ($request->has('image') && ($request->file('image') instanceof UploadedFile)) {
            $image = $this->uploadOne($request->file('image'), 'tag', 'public', Str::random(25));
            $tag->image = $image;
        }

        if ( $tag->save() ) {
            Session::flash('success', 'tag created successfully');
            return redirect()->back();
        }

        Session::flash('error', 'Something went wrong!');
        return redirect()->back();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        return view('admin.tag.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        // return $request;
        // validation
        $this->validate($request, [
            'name' => "required|unique:tags,name,$tag->id",
        ]);

        $tag->name = $request->name;
        $tag->slug = preg_replace('/\s+/u', '-', trim($request->name));
        $tag->description = $request->description;


        if ($request->has('image') && ($request->file('image') instanceof UploadedFile)) {
            if ( $tag->image != null) {
                $this->deleteOne($tag->image);
            }
            $image = $this->uploadOne($request->file('image'), 'categories', 'public', Str::random(25));
            $tag->image = $image;
        }
        $tag->save();
        Session::flash('success', 'Tag updated successfully');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        if($tag){
            $tag->delete();

            Session::flash('success', 'Tag deleted successfully');
        }

        return redirect()->back();
    }
}
