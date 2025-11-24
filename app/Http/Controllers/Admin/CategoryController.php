<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Session;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\UploadAble;
use Illuminate\Http\UploadedFile;

class CategoryController extends Controller
{

    use UploadAble;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $categories = Category::orderBy('created_at', 'DESC')->paginate(20);
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.category.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        // validation
        $this->validate($request, [
            'name' => 'required|unique:categories,name|max:191',
            'slug' => 'required|unique:categories,slug|max:191',
            // 'parent_id' =>  'required|not_in:0',
            'image'     =>  'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $slug = Str::slug($request->slug, '-');
        $execategory = Category::where('slug', $slug)->first();
        if( $execategory != null ){
            Session::flash('error', 'Slug must be unique!');
            return redirect()->back();
        }

            $category = new Category();
            $category->name = $request->name;
            $category->slug = $slug;
            $category->description = $request->description;
            $category->parent_id = $request->parent_id;
            $category->featured = $request->has('featured') ? 1 : 0;
            $category->menu = $request->has('menu') ? 1 : 0;

            if ($request->has('image') && ($request->file('image') instanceof UploadedFile)) {
                $image = $this->uploadOne($request->file('image'), 'categories', 'public', Str::random(25));
                $category->image = $image;
            }

            if ( $category->save() ) {
                Session::flash('success', 'Category created successfully');
                return redirect()->back();
            }

            Session::flash('error', 'Something went wrong!');
            return redirect()->back();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $categories = Category::all();
        return view('admin.category.edit', compact('category','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        // return $request;
        // validation
        $this->validate($request, [
            'name' => 'required|unique:categories,name,'.$category->id.'|max:191',
            'slug' => 'required|unique:categories,slug,'.$category->id.'|max:191',
            'parent_id'=>  'required|not_in:0',
            'image'=>  'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

            $slug = Str::slug($request->slug, '-');
            $execategory = Category::where('slug', $slug)->first();
            if( $execategory != null ){
                if($execategory->slug != $category->slug){
                    Session::flash('error', 'Slug must be unique!');
                    return redirect()->back();

                }
            }

            // $category = new Category();
            $category->name = $request->name;
            $category->slug = $slug;
            $category->description = $request->description;
            $category->parent_id = $request->parent_id;
            $category->featured = $request->has('featured') ? 1 : 0;
            $category->menu = $request->has('menu') ? 1 : 0;

            if ($request->has('image') && ($request->file('image') instanceof UploadedFile)) {
                if ( $category->image != null) {
                    $this->deleteOne($category->image);
                }
                $image = $this->uploadOne($request->file('image'), 'categories', 'public', Str::random(25));
                $category->image = $image;
            }

            if ( $category->save() ) {
                Session::flash('success', 'Category updated successfully');
                return redirect()->back();
            }

            Session::flash('error', 'Something went wrong!');
            return redirect()->back();


        $category->name = $request->name;
        $category->slug = Str::slug($request->name, '-');
        $category->description = $request->description;
        $category->save();

        Session::flash('success', 'Category updated successfully');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if($category){
            $category->delete();

            Session::flash('success', 'Category deleted successfully');
            return redirect()->route('category.index');
        }
    }
}
