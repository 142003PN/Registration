<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration Confirmation Slip</title>
    <style>
        @page {
            size: A4;
            margin: 12mm 15mm 13mm;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: #fff;
            color: #000;
            font-family: "Times New Roman", Times, serif;
        }

        body {
            font-size: 11pt;
            line-height: 1.35;
        }

        .page { position: relative; min-height: 272mm; }

        .document-header {
            position: relative;
            min-height: 37mm;
            padding: 0 33mm 4mm;
            text-align: center;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .university-logo {
            display: block;
            width: 18mm;
            height: auto;
            margin: 0 auto 1mm;
        }

        .university-name {
            margin: 0;
            font-size: 19pt;
            font-weight: normal;
            line-height: 1.05;
        }

        .slip-title {
            margin: 1.5mm 0 1mm;
            font-size: 12pt;
            font-weight: bold;
        }

        .printed-at {
            margin: 0;
            font-size: 9pt;
        }

        .qr-code {
            position: absolute;
            top: 0;
            right: 0;
            width: 29mm;
            text-align: center;
        }

        .qr-code img {
            display: block;
            width: 27mm;
            height: 27mm;
        }

        .qr-caption {
            margin-top: .5mm;
            font-size: 6.5pt;
            line-height: 1.1;
            overflow-wrap: anywhere;
        }

        .programme-title {
            margin: 5mm 0 4mm;
            text-align: center;
            font-size: 15pt;
            font-weight: bold;
            line-height: 1.2;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .divider {
            height: 0;
            margin: 0 0 5mm;
            border: 0;
            border-top: 1px solid #555;
        }

        .registered-courses { page-break-inside: avoid; break-inside: avoid; }

        .section-heading {
            margin: 0 0 3mm;
            font-size: 12pt;
            font-weight: bold;
        }

        .course-intro {
            margin: 0 0 1mm;
        }

        .term-name {
            margin: 0 0 3mm;
            font-weight: bold;
        }

        .course-list {
            margin: 0;
            padding-left: 5mm;
        }

        .course-list li {
            margin: 0 0 .7mm;
            padding-left: 1mm;
        }

        .course-list strong {
            font-weight: bold;
        }

        .course-list em {
            font-style: italic;
        }

        .barcode-section {
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            text-align: center;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .footer-printed,
        .keep-document {
            margin: 0 0 1mm;
            font-size: 8pt;
            font-weight: bold;
            text-align: left;
        }

        .keep-document {
            margin-bottom: 3mm;
        }

        .barcode-image {
            display: block;
            width: 48mm;
            height: 10mm;
            margin: 0 auto 1mm;
        }

        .barcode-student-id {
            margin: 0;
            font-size: 8pt;
            text-align: center;
        }

        @media print {
            body {
                margin: 0;
            }

            .page { min-height: 272mm; }
        }
    </style>
</head>
<body>
    @php
        $studentId = $student->id;
        $printDate = isset($printed_at) ? $printed_at : $printedAt;
        $printDate = $printDate instanceof \DateTimeInterface
            ? $printDate->format('Y-m-d H:i:s')
            : $printDate;
        $printDay = $printDate instanceof \DateTimeInterface
            ? $printDate->format('Y-m-d')
            : substr((string) $printDate, 0, 10);
        $programmeCode = $programme_code ?? null;
        $termName = $term ?? null;
        $confirmationNumber = $confirmation_number ?? null;
    @endphp

    <main class="page">
        <header class="document-header">
            @if(is_file(public_path('images/Logo.png')))
                <img class="university-logo" src="{{ public_path('images/Logo.png') }}" alt="Mukuba University logo">
            @endif
            <h1 class="university-name">Mukuba University</h1>
            <p class="slip-title">REGISTRATION CONFIRMATION SLIP</p>
            <p class="printed-at">PRINTED BY {{ $studentId }} ON {{ $printDate }}</p>

            <div class="qr-code">
                <img src="data:image/svg+xml;base64,{{ base64_encode($qrCode) }}" alt="Registration QR code">
                <div class="qr-caption">
                    {{ $studentId }}-{{ $printDay }}
                    @if($confirmationNumber)
                        -{{ $confirmationNumber }}
                    @endif
                </div>
            </div>
        </header>

        <h2 class="programme-title">{{ $student->program->name }}</h2>
        <hr class="divider">

        <section class="registered-courses">
            <h2 class="section-heading">
                Registered Courses
                @if($programmeCode)
                    - {{ $programmeCode }}
                @endif
            </h2>
            <p class="course-intro">This student has registered for the following courses for the term:</p>
            @if($termName)
                <p class="term-name">{{ $termName }}</p>
            @endif

            <ul class="course-list">
                @foreach($courses as $course)
                    <li>
                        <strong>{{ $course->code }}</strong>
                        -
                        <em>{{ $course->title }}</em>
                    </li>
                @endforeach
            </ul>
        </section>

        <footer class="barcode-section">
            <p class="footer-printed">PRINTED BY {{ $studentId }} ON {{ $printDate }}</p>
            <p class="keep-document">PLEASE KEEP THIS DOCUMENT FOR FUTURE REFERENCE</p>
            <img class="barcode-image" src="data:image/svg+xml;base64,{{ base64_encode($barcode) }}" alt="Registration barcode">
            <p class="barcode-student-id">{{ $studentId }}</p>
        </footer>
    </main>
</body>
</html>
