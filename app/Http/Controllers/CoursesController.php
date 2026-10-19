<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\Programs;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CoursesController extends Controller
{
    public function index(): View
    {
        $courses = Courses::query()
            ->with('program')
            ->orderBy('code')
            ->paginate(10);

        return view('admin.courses.index', compact('courses'));
    }

    public function create(): View
    {
        $programs = Programs::query()->orderBy('name')->get();

        return view('admin.courses.create', compact('programs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules($request));
        $course = Courses::create($validated);

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', 'Course created successfully.');
    }

    public function show(Courses $course): View
    {
        $course->load('program');

        return view('admin.courses.show', compact('course'));
    }

    public function edit(Courses $course): View
    {
        $programs = Programs::query()->orderBy('name')->get();

        return view('admin.courses.edit', compact('course', 'programs'));
    }

    public function update(Request $request, Courses $course): RedirectResponse
    {
        $validated = $request->validate($this->rules($request, $course));
        $course->update($validated);

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Courses $course): RedirectResponse
    {
        $course->delete();

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    private function rules(Request $request, ?Courses $course = null): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('courses', 'code')
                    ->where(fn ($query) => $query->where('program_id', $request->input('program_id')))
                    ->ignore($course),
            ],
            'title' => ['required', 'string', 'max:50'],
            'program_id' => ['required', 'integer', 'exists:programs,id'],
        ];
    }
}
