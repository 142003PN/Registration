<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mukuba University — Registration Confirmation Slip</title>
  <style>
    /* Replica stylesheet for ConfirmationSlip.pdf
       Source uses Times New Roman regular, bold and italic. Place your image files at:
       images/university-logo.png and images/confirmation-qr.png */
    @page { size: Letter; margin: 0; }
    * { box-sizing: border-box; }
    html, body { margin: 0; padding: 0; background: #eee; color: #111; }
    body { font-family: "Times New Roman", Times, serif; font-size: 12px; }
    .document { width: 8.5in; margin: 0 auto; }
    .page {
      width: 8.5in; height: 11in; background: #fff; position: relative;
      padding: .43in .48in .32in; overflow: hidden; page-break-after: always;
    }
    .page:last-child { page-break-after: auto; }
    .masthead { text-align: center; height: 2in; position: relative; padding-top: 1.25in; }
    .logo-slot { position: absolute; top: .03in; left: 50%; transform: translateX(-50%); width: 1.05in; height: .98in; }
    .logo-slot img { width: 100%; height: 100%; object-fit: contain; }
    .qr-slot { position: absolute; top: .12in; right: .02in; width: 1.62in; text-align: center; }
    .qr-slot img { display: block; width: 1.55in; height: 1.55in; object-fit: contain; margin: 0 auto .06in; }
    .qr-slot small { font-size: 7pt; white-space: nowrap; }
    .university { font-size: 22pt; line-height: 1.1; color: #333; }
    .slip-title { font-size: 13pt; line-height: 1.2; color: #333; }
    .printed { display: block; margin-top: .02in; text-align: center; font-size: 9pt; line-height: 1.25; }
    .programme { text-align: center; font-size: 22pt; font-weight: normal; color: #333; margin: .02in 0 .08in; white-space: normal; overflow-wrap: break-word; }
    .rule { border: 0; border-top: 1px solid #999; margin: .08in 0 .19in; }
    h2 { font-size: 18pt; margin: 0 0 .18in; line-height: 1.12; }
    .registered-note { font-size: 12pt; font-weight: bold; margin: 0 0 .015in; }
    .courses { margin: 0 0 .1in; padding-left: .2in; font-size: 10.5pt; line-height: 1.12; }
    .courses li { padding-left: 0; }
    .after-courses { margin-top: .12in; margin-bottom: .19in; }
    .page-two, .page-three { padding-top: .28in; }
    .session-label { text-align: center; font-size: 10pt; margin: .03in 0 .04in; }
    .continuation { width: 91%; margin-top: .05in; }
    .continuation td { height: .28in; }
    .page-three .continuation { margin-top: .08in; }
    .current-balance { margin: .42in .45in 0 .45in; padding-top: .04in; display: flex; justify-content: space-between; font-size: 11pt; font-weight: bold; }
    .page-footer { position: absolute; bottom: .65in; left: .48in; right: .48in; text-align: center; font-size: 10pt; }
    .page-footer p { margin: .06in 0; }
    .barcode-slot { display: flex; flex-direction: column; align-items: center; margin: 0 auto .08in; page-break-inside: avoid; break-inside: avoid; }
    .barcode-slot svg { display: block; width: auto; max-width: 2.8in; max-height: .48in; }
    .barcode-slot small { margin-top: .025in; font-family: Arial, sans-serif; font-size: 7pt; line-height: 1; letter-spacing: .04em; }
    .student-number { position: absolute; top: .42in; right: .03in; font-size: 10pt; }
    .slip-actions { display: flex; justify-content: center; gap: 10px; padding: 16px; }
    .slip-action { border: 0; border-radius: 6px; padding: 11px 18px; background: #176044; color: #fff; font: 600 14px Arial, sans-serif; text-decoration: none; cursor: pointer; }
    .slip-action:hover { background: #104c35; }
    @media screen {
      .page { margin: 18px auto; box-shadow: 0 2px 12px #bbb; }
    }
    @media screen and (max-width: 8.5in) {
      .document, .page { width: 100%; }
    }
    @media screen and (max-width: 600px) {
      html, body { min-width: 320px; }
      .document { width: 100%; padding: 0 8px; }
      .page { height: auto; min-height: 0; margin: 10px auto; padding: 18px 14px 24px; overflow: visible; }
      .masthead { height: auto; min-height: 176px; padding-top: 83px; }
      .logo-slot { top: 0; left: 0; transform: none; width: 62px; height: 58px; }
      .qr-slot { top: 0; right: 0; width: 76px; }
      .qr-slot img { width: 68px; height: 68px; }
      .qr-slot small { font-size: 6px; }
      .university { font-size: 19px; }
      .slip-title { font-size: 11px; }
      .printed { font-size: 9px; }
      .programme { font-size: 20px; }
      h2 { font-size: 17px; }
      .registered-note { font-size: 14px; line-height: 1.35; }
      .courses { padding-left: 20px; font-size: 14px; line-height: 1.4; }
      .page-footer { position: static; margin-top: 30px; font-size: 12px; }
      .barcode-slot svg { max-width: 100%; }
      .student-number { top: auto; right: auto; position: static; text-align: right; }
      .slip-actions { position: sticky; bottom: 0; z-index: 2; background: #eee; padding: 10px 4px; }
      .slip-action { flex: 1; text-align: center; padding: 12px 8px; }
    }
    @media print {
      html, body { background: #fff; }
      .document { margin: 0; }
      .page { margin: 0; box-shadow: none; }
      .slip-actions { display: none; }
    }
    @media screen and (min-width: 601px) {
      .page { height: auto; min-height: 11in; }
      .page-footer { position: static; margin-top: 2in; }
    }
  </style>
</head>
<body>
  <nav class="slip-actions" aria-label="Slip actions">
    <button class="slip-action" type="button" onclick="window.print()">Print slip</button>
  <main class="document">
    <section class="page page-one">
      <header class="masthead">
        <div class="logo-slot"><!-- Add your university crest image here --><img src="{{ asset('images/Logo.png') }}" alt="Mukuba University logo"></div>
        <div class="qr-slot"><!-- Add your QR image here --><img src="data:image/svg+xml;base64,{{ base64_encode($qr_code) }}" alt="Confirmation QR code"><small>22010146-2026-10-19-29722</small></div>
        <div class="university">Mukuba University</div>
        <div class="slip-title">REGISTRATION CONFIRMATION SLIP</div>
        <div class="printed">PRINTED BY {{ $student->id }} ON {{ now()->format('Y-m-d H:i:s') }}</div>
      </header>

      <h1 class="programme">{{ $student->program->name }}</h1>
      <hr class="rule">
      <h2>Registered Courses - FT-2026- {{ $student->program->name }} -Y1 -S1</h2>
      <p class="registered-note">This student has registered for the following courses for the Semester: <u>FullTime 2026/2027({{ now()->format('d, F') }})</u></p>
      <ul class="courses">
        @foreach($courses as $course)
          <li><b>{{ $course->code }}</b> - <i>{{ $course->title }}</i></li>
        @endforeach
      </ul>
      <hr class="rule after-courses">
      <footer class="page-footer">
        <p>PRINTED BY {{ $student->id }} ON {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>PLEASE KEEP THIS DOCUMENT FOR FUTURE REFERENCE</p>
        <div class="barcode-slot" aria-label="Barcode for student number {{ $student_number }}">
          {!! $barcode_svg !!}
          <small>{{ $student_number }}</small>
        </div>
      </footer>
    </section>
  </main>
</body>
</html>
