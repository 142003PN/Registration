<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\Programs;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Exception as SpreadsheetException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception as SpreadsheetReaderException;

class CoursesController extends Controller
{
    public function index(): View
    {
        $courses = Courses::query()
            ->with('program')
            ->orderBy('code')
            ->get();

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
        $lastRow = $worksheet->getHighestDataRow();
        $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($worksheet->getHighestDataColumn());

        if ($lastRow > 5001) {
            $spreadsheet->disconnectWorksheets();
            throw ValidationException::withMessages([
                'file' => 'The spreadsheet can contain no more than 5,000 course rows.',
            ]);
        }

        $headers = [];
        for ($column = 1; $column <= $lastColumn; $column++) {
            $columnLetter = Coordinate::stringFromColumnIndex($column);
            $headers[mb_strtolower(trim((string) $worksheet->getCell($columnLetter.'1')->getValue()))] = $columnLetter;
        }

        $requiredHeaders = ['programme name', 'course code', 'course title'];
        if (array_diff($requiredHeaders, array_keys($headers)) !== []) {
            $spreadsheet->disconnectWorksheets();
            throw ValidationException::withMessages([
                'file' => 'The first row must contain the column headings: Programme Name, Course Code, and Course Title.',
            ]);
        }

        $rows = [];
        $rowErrors = [];
        for ($rowIndex = 2; $rowIndex <= $lastRow; $rowIndex++) {
            $programName = trim((string) $worksheet->getCell($headers['programme name'].$rowIndex)->getValue());
            $code = trim((string) $worksheet->getCell($headers['course code'].$rowIndex)->getValue());
            $title = trim((string) $worksheet->getCell($headers['course title'].$rowIndex)->getValue());

            if ($programName === '' && $code === '' && $title === '') {
                continue;
            }

            if ($programName === '' || $code === '' || $title === '') {
                $rowErrors[] = 'Row '.$rowIndex.' must include a programme name, course code, and course title.';
                continue;
            }
            if (mb_strlen($code) > 20 || mb_strlen($title) > 100) {
                $rowErrors[] = 'Row '.$rowIndex.' has a course code longer than 10 characters or a course title longer than 50 characters.';
                continue;
            }

            $rows[] = ['row' => $rowIndex, 'program_name' => $programName, 'code' => $code, 'title' => $title];
        }
        $spreadsheet->disconnectWorksheets();

        if ($rowErrors !== []) {
            throw ValidationException::withMessages(['file' => $rowErrors]);
        }

        $programNames = array_values(array_unique(array_column($rows, 'program_name')));
        $programs = Programs::query()->whereIn('name', $programNames)->get(['id', 'name']);
        $programIds = [];
        foreach ($programs as $program) {
            $programIds[mb_strtolower($program->name)] = $program->id;
        }

        $courseRows = [];
        foreach ($rows as $row) {
            $programId = $programIds[mb_strtolower($row['program_name'])] ?? null;
            if ($programId === null) {
                $rowErrors[] = 'Row '.$row['row'].' references programme "'.$row['program_name'].'", which does not exist.';
                continue;
            }

            $courseRows[] = [
                'row' => $row['row'],
                'program_id' => $programId,
                'code' => $row['code'],
                'title' => $row['title'],
            ];
        }

        if ($rowErrors !== []) {
            throw ValidationException::withMessages(['file' => $rowErrors]);
        }

        $seen = [];
        foreach ($courseRows as $row) {
            $key = $row['program_id'].'|'.mb_strtolower($row['code']);
            if (isset($seen[$key])) {
                $rowErrors[] = 'Row '.$row['row'].' duplicates course code "'.$row['code'].'" within its programme.';
            }
            $seen[$key] = true;
        }

        $existingKeys = [];
        foreach (array_chunk(array_unique(array_column($courseRows, 'program_id')), 500) as $programIdChunk) {
            $existing = DB::table('courses')->whereIn('program_id', $programIdChunk)->get(['program_id', 'code']);
            foreach ($existing as $course) {
                $existingKeys[$course->program_id.'|'.mb_strtolower($course->code)] = true;
            }
        }
        foreach ($courseRows as $row) {
            $key = $row['program_id'].'|'.mb_strtolower($row['code']);
            if (isset($existingKeys[$key])) {
                $rowErrors[] = 'Row '.$row['row'].' uses course code "'.$row['code'].'", which already exists for that programme.';
            }
        }

        if ($rowErrors !== []) {
            throw ValidationException::withMessages(['file' => $rowErrors]);
        }

        $timestamp = now();
        DB::transaction(function () use ($courseRows, $timestamp): void {
            foreach (array_chunk($courseRows, 500) as $chunk) {
                DB::table('courses')->insert(array_map(
                    fn (array $row): array => [
                        'program_id' => $row['program_id'],
                        'code' => $row['code'],
                        'title' => $row['title'],
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ],
                    $chunk
                ));
            }
        });

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Imported '.count($courseRows).' course(s) successfully.');
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
