<?php

namespace App\Http\Controllers;

use App\Models\Programs;
use App\Models\Students;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function export(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet;
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->setTitle('Registered Students');
        $worksheet->fromArray(
            ['Student ID', 'First Name', 'Middle Name', 'Last Name', 'Programme'],
            null,
            'A1'
        );
        $worksheet->getStyle('A1:E1')->getFont()->setBold(true);
        $worksheet->freezePane('A2');
        $worksheet->setAutoFilter('A1:E1');

        $row = 2;
        Students::query()
            ->with('program')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->chunk(500, function ($students) use ($worksheet, &$row): void {
                foreach ($students as $student) {
                    $values = [
                        $student->id,
                        $student->first_name,
                        $student->middle_name ?? '',
                        $student->last_name,
                        $student->program->name,
                    ];

                    foreach ($values as $column => $value) {
                        $worksheet->setCellValueExplicit(
                            $worksheet->getCell([$column + 1, $row])->getCoordinate(),
                            (string) $value,
                            DataType::TYPE_STRING
                        );
                    }

                    $row++;
                }
            });

        foreach (range('A', 'E') as $column) {
            $worksheet->getColumnDimension($column)->setAutoSize(true);
        }

        return response()->streamDownload(function () use ($spreadsheet): void {
            (new Xlsx($spreadsheet))->save('php://output');
            $spreadsheet->disconnectWorksheets();
        }, 'registered-students-'.now()->format('Y-m-d').'.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
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
