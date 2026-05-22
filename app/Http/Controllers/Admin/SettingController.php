<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\Category;
use App\Http\Requests\Post\PostRequest;
use App\Models\Document;
use App\Models\Expense;
use App\Models\Post\Post;
use App\Models\Product;
use App\Models\Role;
use App\Models\SmsTemplate;
use App\Models\Student\Student;
use App\Models\StudentStatus;
use App\Models\StudnetStatus;
use App\Models\Study;
use App\Models\Subject;
use App\Models\Tag;
use App\Models\UniversityStatus;
use App\Support\StatusDropdownCache;
use Database\Seeders\StudentStatusesTableSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        if (request()->is('setting/products') || request()->is('setting/products/*')) {
            $data = Product::orderBy('id', 'DESC');
            $d['title'] = 'Product List';
            $d['type'] = 'product';
            $d['url'] = 'setting.products';
            $d['data'] = $data->paginate(20);
        }
        if (request()->is('setting/expenses') || request()->is('setting/expenses/*')) {
            $data = Expense::orderBy('id', 'DESC');
            $d['title'] = 'Expenses List';
            $d['type'] = 'expense';
            $d['url'] = 'setting.expenses';
            $d['data'] = $data->paginate(20);
        }
        if (request()->is('setting/documents') || request()->is('setting/documents/*')) {
            $data = Document::orderBy('id', 'DESC');
            $d['title'] = 'Document List';
            $d['type'] = 'document';
            $d['url'] = 'setting.documents';
            $d['data'] = $data->paginate(20);
        }
        if (request()->is('setting/roles') || request()->is('setting/roles/*')) {
            $data = Role::orderBy('id', 'DESC');
            $d['title'] = 'Role List';
            $d['type'] = 'role';
            $d['url'] = 'setting.roles';
            $d['data'] = $data->paginate(20);
        }
        if (request()->is('setting/studies') || request()->is('setting/studies/*')) {
            $data = Study::orderBy('id', 'DESC');
            $d['title'] = 'studies List';
            $d['type'] = 'studies';
            $d['url'] = 'setting.studies';
            $d['data'] = $data->paginate(20);
        }
        
        if (request()->is('setting/subjects') || request()->is('setting/subjects/*')) {
            $data = Subject::orderBy('id', 'DESC');
            $d['title'] = 'subjects List';
            $d['type'] = 'subject';
            $d['url'] = 'setting.subjects';
            $d['data'] = $data->paginate(20);
        }
        if (request()->is('setting/student_status') || request()->is('setting/student_status/*')) {
            $data = StudentStatus::orderBy('id', 'DESC');
            $d['title'] = 'student_status List';
            $d['type'] = 'student_status';
            $d['url'] = 'setting.student_status';
            $d['data'] = $data->paginate(20);
        }
        if (request()->is('setting/university_status') || request()->is('setting/university_status/*')) {
            $data = UniversityStatus::orderBy('id', 'DESC');
            $d['title'] = 'university_status List';
            $d['type'] = 'university_status';
            $d['url'] = 'setting.university_status';
            $d['data'] = $data->paginate(20);
        }
        if (request()->is('setting/sms_template') || request()->is('setting/sms_template/*')) {
            $data = SmsTemplate::orderBy('id', 'DESC');
            $d['title'] = 'SMS Template List';
            $d['type'] = 'SMS Template';
            $d['url'] = 'setting.sms_template';
            $d['data'] = $data->paginate(20);
        }
        $d['request'] = $request;
        return view('admin.setting.index')->with($d);
    }

    public function create()
    {
        $d = [
        'title' => 'Default Title',
        'type' => '',
        'url' => '',
        ];
        
        if (request()->is('setting/expenses') || request()->is('setting/expenses/*')) {
            // $data = Product::orderBy('id', 'DESC');
            $d['title'] = 'Product Create';
            $d['type'] = 'expense';
            $d['url'] = 'setting.expenses';
        }
        if (request()->is('setting/products') || request()->is('setting/products/*')) {
            // $data = Product::orderBy('id', 'DESC');
            $d['title'] = 'Product Create';
            $d['type'] = 'product';
            $d['url'] = 'setting.products';
        }
        if (request()->is('setting/documents') || request()->is('setting/documents/*')) {
            $d['title'] = 'documents Create';
            $d['type'] = 'document';
            $d['url'] = 'setting.documents';
        }
        if (request()->is('setting/studies') || request()->is('setting/studies/*')) {
            $d['title'] = 'studies Create';
            $d['type'] = 'studies';
            $d['url'] = 'setting.studies';
        }
        if (request()->is('setting/subjects') || request()->is('setting/subjects/*')) {
            $d['title'] = 'subjects Create';
            $d['type'] = 'subject';
            $d['url'] = 'setting.subjects';
        }
        if (request()->is('setting/student_status') || request()->is('setting/student_status/*')) {
            $d['title'] = 'student_status Create';
            $d['type'] = 'student_status';
            $d['url'] = 'setting.student_status';
        }
        if (request()->is('setting/university_status') || request()->is('setting/university_status/*')) {
            $d['title'] = 'university_status Create';
            $d['type'] = 'university_status';
            $d['url'] = 'setting.university_status';
        }
        if (request()->is('setting/sms_template') || request()->is('setting/sms_template/*')) {
            // $data = SmsTemplate::orderBy('id', 'DESC');
            $d['title'] = 'SMS Template List';
            $d['type'] = 'SMS Template';
            $d['url'] = 'setting.sms_template';
        }

        $d['tags'] = Tag::all();
        $d['categories'] = Category::all();
        // return $d;
        return view('admin.setting.create')->with($d);
    }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'name' => 'required',
        ]);

        DB::beginTransaction();
        if ($request->post_type == 'product') {
            $model = new Product();
        }
        if ($request->post_type == 'expense') {
            $model = new Expense();
        }
        if ($request->post_type == 'studies') {
            $model = new Study();
        }
        if ($request->post_type == 'student_status') {
            $model = new StudentStatus();
        }
        if ($request->post_type == 'university_status') {
            $model = new UniversityStatus();
        }
        if ($request->post_type == 'subject') {
            $model = new Subject();
        }
        if ($request->post_type == 'document') {
            $model = new Document();
        }
        if ($request->post_type == 'SMS Template') {
            $model = new SmsTemplate();
        }
        try {
            $modelFillableData = $request->only($model->getFillable());
            $model->fill($modelFillableData)->save();
            DB::commit();
            $this->forgetStatusDropdownCache($request->post_type);

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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (request()->is('setting/products') || request()->is('setting/products/*')) {
            $data['post'] = Product::findOrFail($id);
            $data['title'] = 'Product Edit';
            $data['type'] = 'product';
            $data['url'] = 'setting.products';
        }
        if (request()->is('setting/expenses') || request()->is('setting/expenses/*')) {
            $data['post'] = Expense::findOrFail($id);
            $data['title'] = 'Expense Edit';
            $data['type'] = 'expense';
            $data['url'] = 'setting.expenses';
        }
        if (request()->is('setting/studies') || request()->is('setting/studies/*')) {
            $data['post'] = Study::findOrFail($id);
            $data['title'] = 'Study Edit';
            $data['type'] = 'study';
            $data['url'] = 'setting.studies';
        }
        if (request()->is('setting/documents') || request()->is('setting/documents/*')) {
            $data['post'] = Document::findOrFail($id);
            $data['title'] = 'Document Edit';
            $data['type'] = 'document';
            $data['url'] = 'setting.documents';
        }
        if (request()->is('setting/subjects') || request()->is('setting/subjects/*')) {
            $data['post'] = Subject::findOrFail($id);
            $data['title'] = 'Subject Edit';
            $data['type'] = 'subject';
            $data['url'] = 'setting.subjects';
        }
        if (request()->is('setting/student_status') || request()->is('setting/student_status/*')) {
            $data['post'] = StudentStatus::findOrFail($id);
            $data['title'] = 'student_status Edit';
            $data['type'] = 'student_status';
            $data['url'] = 'setting.student_status';
        }
        if (request()->is('setting/university_status') || request()->is('setting/university_status/*')) {
            $data['post'] = UniversityStatus::findOrFail($id);
            $data['title'] = 'university_status Edit';
            $data['type'] = 'university_status';
            $data['url'] = 'setting.university_status';
        }

        if (request()->is('setting/roles') || request()->is('setting/roles/*')) {
            $data['post'] = Role::findOrFail($id);
            $data['title'] = 'Role Edit';
            $data['type'] = 'role';
            $data['url'] = 'setting.roles';
        }

        if (request()->is('setting/sms_template') || request()->is('setting/sms_template/*')) {
            $data['post'] = SmsTemplate::findOrFail($id);
            $data['title'] = 'SMS Template List';
            $data['type'] = 'SMS Template';
            $data['url'] = 'setting.sms_template';
        }

        $data['tags'] = Tag::all();
        $data['categories'] = Category::all();
        // return $data;
        return view('admin.setting.edit')->with($data);;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return $request;
        $request->validate([
            'name' => 'required',
        ]);

        DB::beginTransaction();
        if ($request->post_type == 'product') {
            $model = Product::findOrFail($id);
        }
        if ($request->post_type == 'expense') {
            $model = Expense::findOrFail($id);
        }
        if ($request->post_type == 'study') {
            $model = Study::findOrFail($id);
        }
        if ($request->post_type == 'subject') {
            $model = Subject::findOrFail($id);
        }
        if ($request->post_type == 'university_status') {
            $model = UniversityStatus::findOrFail($id);
        }
        if ($request->post_type == 'student_status') {
            $model = StudentStatus::findOrFail($id);
        }
        if ($request->post_type == 'role') {
            $model = Role::findOrFail($id);
        }
        if ($request->post_type == 'document') {
            $model = Document::findOrFail($id);
        }
        if ($request->post_type == 'SMS Template') {
            $model = SmsTemplate::findOrFail($id);
        }
        try {
            $modelFillableData = $request->only($model->getFillable());
            $model->fill($modelFillableData)->save();
            DB::commit();
            $this->forgetStatusDropdownCache($request->post_type);

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
    public function destroy(Request $request, $id)
    {
        // return $request;
        DB::beginTransaction();
        if ($request->post_type == 'product') {
            $model = Product::findOrFail($id);
        }
        if ($request->post_type == 'expense') {
            $model = Expense::findOrFail($id);
        }
        if ($request->post_type == 'studies') {
            $model = Study::findOrFail($id);
        }
        if ($request->post_type == 'subject') {
            $model = Subject::findOrFail($id);
        }
        if ($request->post_type == 'student_status') {
            $model = StudentStatus::findOrFail($id);
        }
        if ($request->post_type == 'university_status') {
            $model = UniversityStatus::findOrFail($id);
        }
        if ($request->post_type == 'document') {
            $model = Document::findOrFail($id);
        }
        if ($request->post_type == 'SMS Template') {
            $model = SmsTemplate::findOrFail($id);
        }
        try {
            if ($model) {
                $model->delete();
            }
            DB::commit();
            $this->forgetStatusDropdownCache($request->post_type);

            return redirect()->back()->with('success', 'Successfully Deleted');
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
            return redirect()->back()->with('error', 'Operation Failed.');;
        }
    }

    private function forgetStatusDropdownCache(?string $postType): void
    {
        if ($postType === 'student_status') {
            StatusDropdownCache::forgetStudentStatuses();
        }

        if ($postType === 'university_status') {
            StatusDropdownCache::forgetUniversityStatuses();
        }
    }
}
