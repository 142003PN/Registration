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
    .masthead { text-align: center; height: 1.72in; position: relative; padding-top: 1.25in; }
    .logo-slot { position: absolute; top: .03in; left: 50%; transform: translateX(-50%); width: 1.05in; height: .98in; }
    .logo-slot img { width: 100%; height: 100%; object-fit: contain; }
    .qr-slot { position: absolute; top: .12in; right: .02in; width: 1.62in; text-align: center; }
    .qr-slot img { display: block; width: 1.55in; height: 1.55in; object-fit: contain; margin: 0 auto .06in; }
    .qr-slot small { font-size: 7pt; white-space: nowrap; }
    .university { font-size: 22pt; line-height: 1.1; color: #333; }
    .slip-title { font-size: 13pt; line-height: 1.2; color: #333; }
    .printed { font-size: 9pt; line-height: 1.25; }
    .programme { text-align: center; font-size: 22pt; font-weight: normal; color: #333; margin: .02in 0 .08in; white-space: nowrap; }
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
    .student-number { position: absolute; top: .42in; right: .03in; font-size: 10pt; }
    @media screen {
      .page { margin: 18px auto; box-shadow: 0 2px 12px #bbb; }
    }
    @media print {
      html, body { background: #fff; }
      .document { margin: 0; }
      .page { margin: 0; box-shadow: none; }
    }
  </style>
</head>
<body>
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
      <p class="registered-note">This student has registered for the following courses for the term: <u>FullTime 2025/2026(06 October)</u></p>
      <ul class="courses">
        @foreach($courses as $course)
          <li><b>{{ $course->code }}</b> - <i>{{ $course->title }}</i></li>
        @endforeach
      </ul>
      <hr class="rule after-courses">
      <footer class="page-footer">
        <p>PRINTED BY {{ $student->id }} ON {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>PLEASE KEEP THIS DOCUMENT FOR FUTURE REFERENCE</p>
      </footer>
    </section>
  </main>
  <script>
    window.addEventListener('load', () => window.print());
  </script>
</body>
</html>
