<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CSCAExam;

class CSCAExamController extends Controller
{
    public function index()
    {
        $exams = CSCAExam::orderBy('name')->get();
        return view('admin.settings.csca_exam.index', compact('exams'));
    }

    public function create()
    {
        return view('admin.settings.csca_exam.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:csca_exams,name',
        ]);
        CSCAExam::create(['name' => $request->name]);
        return redirect()->route('setting.csca_exam.index')->with('success', 'Exam created successfully.');
    }

    public function edit($id)
    {
        $exam = CSCAExam::findOrFail($id);
        return view('admin.settings.csca_exam.edit', compact('exam'));
    }

    public function update(Request $request, $id)
    {
        $exam = CSCAExam::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255|unique:csca_exams,name,' . $exam->id,
        ]);
        $exam->update(['name' => $request->name]);
        return redirect()->route('setting.csca_exam.index')->with('success', 'Exam updated successfully.');
    }

    public function destroy($id)
    {
        $exam = CSCAExam::findOrFail($id);
        $exam->delete();
        return redirect()->route('setting.csca_exam.index')->with('success', 'Exam deleted successfully.');
    }
}
