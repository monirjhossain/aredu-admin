<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScholarshipFormOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ScholarshipFormOptionController extends Controller
{
    private $groups = [
        'coverage' => 'Coverage',
        'degree' => 'Degree',
        'language' => 'Teaching language',
        'intake' => 'Intake',
        'scholarship_type' => 'Scholarship type',
    ];

    public function index()
    {
        $options = ScholarshipFormOption::query()->orderBy('sort')->orderBy('label')->get()->groupBy('group');

        return view('admin.post.scholarship_options', [
            'groups' => $this->groups,
            'options' => $options,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'group' => 'required|in:' . implode(',', array_keys($this->groups)),
            'label' => 'required|string|max:120',
        ]);

        ScholarshipFormOption::firstOrCreate(
            ['group' => $data['group'], 'label' => trim($data['label'])],
            ['sort' => ScholarshipFormOption::where('group', $data['group'])->count() + 1]
        );

        Session::flash('success', 'Option added.');

        return redirect()->route('scholarship-options.index');
    }

    public function destroy(ScholarshipFormOption $scholarship_option)
    {
        $scholarship_option->delete();
        Session::flash('success', 'Option removed.');

        return redirect()->route('scholarship-options.index');
    }
}
