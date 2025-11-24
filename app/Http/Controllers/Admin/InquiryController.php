<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Session;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inquiry\InquiryRequest;
use App\Models\Inquiry\Inquiry;
use App\Traits\UploadAble;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class InquiryController extends Controller
{

    use UploadAble;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $inquiries = Inquiry::orderBy('created_at', 'DESC')->paginate(20);
        return view('admin.inquiries.index', compact('inquiries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = 'Inquiry Create';
        $data['type'] = 'inquiry';
        $data['url'] = 'inquiries';
        return view('admin.inquiries.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InquiryRequest $request)
    {
        DB::beginTransaction();
        try {
            $inquiry = new Inquiry();
            $inquiryFillableData = $request->only($inquiry->getFillable());
            $inquiry->fill($inquiryFillableData)->save();
            DB::commit();
            Session::flash('success', 'Created successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('error', 'Something went wrong!');
            return redirect()->back();
        }
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

    public function edit(Inquiry $inquiry)
    {
        $data['inquiry'] = $inquiry;
        $data['title'] = 'Inquiry Edit';
        $data['type'] = 'inquiry';
        $data['url'] = 'inquiries';
        return view('admin.inquiries.edit')->with($data);
    }

    public function update(InquiryRequest $request, Inquiry $inquiry)
    {
        DB::beginTransaction();
        try {
            $inquiryFillableData = $request->only($inquiry->getFillable());
            $inquiry->fill($inquiryFillableData)->save();
            DB::commit();
            Session::flash('success', 'Updated Successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('error', 'Something went wrong!');
            return redirect()->back();
        }
    }

    public function destroy(Inquiry $inquiry)
    {
        if($inquiry){
            $inquiry->delete();
            Session::flash('success', 'Inquiry deleted successfully');
            return redirect()->route('inquiries.index');
        }
    }
}
