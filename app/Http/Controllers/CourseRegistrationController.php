<?php

namespace App\Http\Controllers;

use App\Models\CourseRegistration;
use App\Models\Courses;
use App\Models\Programs;
use App\Models\Students;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\SvgWriter;
use Picqer\Barcode\BarcodeGenerator;
use Picqer\Barcode\BarcodeGeneratorSVG;

class CourseRegistrationController extends Controller
{
    public function create(): View
    {
        return view('registration.create', [
            'programs' => Programs::query()->orderBy('name')->get(),
        ]);
    }

    public function courses(Programs $program): JsonResponse
    {
        return response()->json($program->courses()->orderBy('code')->get(['id', 'code', 'title']));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:15'],
            'middle_name' => ['nullable', 'string', 'max:15'],
            'last_name' => ['required', 'string', 'max:15'],
            'program_id' => ['required', 'integer', 'exists:programs,id'],
            'course_ids' => ['required', 'array', 'min:1'],
            'course_ids.*' => [
                'required', 'integer',
                Rule::exists('courses', 'id')->where('program_id', $request->input('program_id')),
            ],
        ]);

        $studentId = DB::transaction(function () use ($data) {
            $student = Students::create([
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'] ?? null,
                'last_name' => $data['last_name'],
                'program_id' => $data['program_id'],
            ]);

            foreach (array_unique($data['course_ids']) as $courseId) {
                CourseRegistration::create([
                    'student_id' => $student->id,
                    'course_id' => $courseId,
                ]);
            }

            return $student->id;
        });

        return redirect()->route('registration.slip', $studentId);
    }

    public function lookup(): View
    {
        return view('registration.lookup');
    }

    public function find(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'student_id' => ['required', 'integer', 'exists:students,id'],
        ], [
            'student_id.exists' => 'No registration was found for that student ID.',
        ]);

        return redirect()->route('registration.slip', $data['student_id']);
    }

    public function slip(Students $student)
    {
        $student->load(['program', 'registrations.course']);

        if ($student->registrations->isEmpty()) {
            abort(404, 'No course registration was found for this student.');
        }

        $courses = $student->registrations->pluck('course')->filter()->sortBy('code')->values();
        $printedAt = now();
        $qrPayload = implode('-', [$student->id, $printedAt->format('Y-m-d'), $student->created_at?->format('His') ?? 'REG']);
        return response()->view('registration.slip3', [
            'student' => $student,
            'courses' => $courses,
            'qr_code' => $this->qrCodeSvg($qrPayload),
        ]);
    }

    private function qrCodeSvg(string $value): string
    {
        $qr = new QrCode(
            data: $value,
            errorCorrectionLevel: ErrorCorrectionLevel::Medium,
            size: 180,
            margin: 2,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
        );

        return (new SvgWriter())->write($qr, options: [
            SvgWriter::WRITER_OPTION_EXCLUDE_XML_DECLARATION => true,
        ])->getString();
    }

    private function barcodeSvg(string $value): string
    {
        return (new BarcodeGeneratorSVG())->getBarcode(
            $value,
            BarcodeGenerator::TYPE_CODE_128,
            1.25,
            40,
            '#111111',
        );
    }
}
