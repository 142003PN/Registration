<?php

namespace App\Http\Controllers;

use App\Models\Programs;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProgramsController extends Controller
{
    public function index(): View
    {
        $programs = Programs::query()
            ->orderBy('name')
            ->paginate(10);

        return view('admin.programs.index', compact('programs'));
    }

    public function create(): View
    {
        return view('admin.programs.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:programs,name'],
        ]);

        $program = Programs::create($validated);

        return redirect()
            ->route('admin.programs.show', $program)
            ->with('success', 'Programme created successfully.');
    }

    public function show(Programs $program): View
    {
        return view('admin.programs.show', compact('program'));
    }

    public function edit(Programs $program): View
    {
        return view('admin.programs.edit', compact('program'));
    }

    public function update(Request $request, Programs $program): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('programs', 'name')->ignore($program),
            ],
        ]);

        $program->update($validated);

        return redirect()
            ->route('admin.programs.show', $program)
            ->with('success', 'Programme updated successfully.');
    }

    public function destroy(Programs $program): RedirectResponse
    {
        if ($program->students()->exists()) {
            return redirect()
                ->route('admin.programs.show', $program)
                ->with('error', 'This programme cannot be deleted while students are assigned to it.');
        }

        $program->delete();

        return redirect()
            ->route('admin.programs.index')
            ->with('success', 'Programme deleted successfully.');
    }
}
