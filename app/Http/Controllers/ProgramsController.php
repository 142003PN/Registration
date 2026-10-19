<?php

namespace App\Http\Controllers;

use App\Models\Programs;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Exception as SpreadsheetException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception as SpreadsheetReaderException;

class ProgramsController extends Controller
{
    public function index(): View
    {
        $programs = Programs::query()
            ->orderBy('name')
            ->get();

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

    public function import(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
        ]);

        try {
            $spreadsheet = IOFactory::load($validated['file']->getRealPath());
        } catch (SpreadsheetReaderException|SpreadsheetException) {
            throw ValidationException::withMessages([
                'file' => 'The uploaded spreadsheet could not be read. Check that it is a valid Excel or CSV file.',
            ]);
        }

        $worksheet = $spreadsheet->getActiveSheet();
        $lastRow = $worksheet->getHighestDataRow('A');

        if ($lastRow > 5001) {
            $spreadsheet->disconnectWorksheets();
            throw ValidationException::withMessages([
                'file' => 'The spreadsheet can contain no more than 5,000 programme rows.',
            ]);
        }

        $header = mb_strtolower(trim((string) $worksheet->getCell('A1')->getValue()));
        if (! in_array($header, ['name', 'program name', 'programme name'], true)) {
            $spreadsheet->disconnectWorksheets();
            throw ValidationException::withMessages([
                'file' => 'The first cell must be a column heading: Name, Program Name, or Programme Name.',
            ]);
        }

        $programNames = [];
        $rowErrors = [];

        for ($rowIndex = 2; $rowIndex <= $lastRow; $rowIndex++) {
            $name = trim((string) $worksheet->getCell('A'.$rowIndex)->getValue());
            if ($name === '') {
                continue;
            }

            if (mb_strlen($name) > 200) {
                $rowErrors[] = 'Row '.$rowIndex.' has a programme name longer than 200 characters.';
                continue;
            }

            $programNames[] = $name;
        }

        $spreadsheet->disconnectWorksheets();

        if ($rowErrors !== []) {
            throw ValidationException::withMessages(['file' => $rowErrors]);
        }

        $uniqueNames = [];
        foreach ($programNames as $name) {
            $uniqueNames[mb_strtolower($name)] ??= $name;
        }

        $existingNames = [];

        foreach (array_chunk(array_values($uniqueNames), 500) as $nameChunk) {
            $existingNames = array_merge(
                $existingNames,
                DB::table('programs')->whereIn('name', $nameChunk)->pluck('name')->all()
            );
        }

        $existingNameKeys = array_fill_keys(array_map('mb_strtolower', $existingNames), true);
        $newNames = [];
        foreach ($uniqueNames as $key => $name) {
            if (! isset($existingNameKeys[$key])) {
                $newNames[] = $name;
            }
        }

        $timestamp = now();

        DB::transaction(function () use ($newNames, $timestamp): void {
            foreach (array_chunk($newNames, 500) as $nameChunk) {
                DB::table('programs')->insert(array_map(
                    fn (string $name): array => [
                        'name' => $name,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ],
                    $nameChunk
                ));
            }
        });

        $skipped = count($programNames) - count($newNames);

        return redirect()
            ->route('admin.programs.index')
            ->with('success', 'Imported '.count($newNames).' programme(s); skipped '.$skipped.' duplicate(s).');
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
