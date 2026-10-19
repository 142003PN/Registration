<?php

namespace App\Http\Controllers;

use App\Models\Programs;
use App\Models\Students;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentsController extends Controller
{
    public function index(): View
    {
        $students = Students::query()
            ->with('program')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('admin.students.index', compact('students'));
    }

    public function create(): View
    {
        $programs = Programs::query()->orderBy('name')->get();

        return view('admin.students.create', compact('programs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $student = Students::create($request->validate($this->rules()));

        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Student created successfully.');
    }

    public function show(Students $student): View
    {
        $student->load('program');

        return view('admin.students.show', compact('student'));
    }

    public function edit(Students $student): View
    {
        $programs = Programs::query()->orderBy('name')->get();

        return view('admin.students.edit', compact('student', 'programs'));
    }

    public function update(Request $request, Students $student): RedirectResponse
    {
        $student->update($request->validate($this->rules()));

        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Students $student): RedirectResponse
    {
        $student->delete();

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }

    private function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:15'],
            'middle_name' => ['nullable', 'string', 'max:15'],
            'last_name' => ['required', 'string', 'max:15'],
            'program_id' => ['required', 'integer', 'exists:programs,id'],
        ];
    }
}
