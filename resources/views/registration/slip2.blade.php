<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Mukuba University - Registration Confirmation Slip</title>
<style>
  @page { size: A4; margin: 10mm; }

  * { box-sizing: border-box; }

  html, body {
    margin: 0;
    padding: 0;
    background: #fff;
  }

  body {
    font-family: "Times New Roman", Times, "Liberation Serif", serif;
    font-size: 16px;
    color: #000;
    line-height: normal;
  }

  .page {
    width: 950px;
    max-width: 100%;
    margin: 0 auto;
    padding: 55px 54px 40px 54px;
  }

  /* ---------- Header ---------- */
  .header {
    position: relative;
    height: 215px;
    text-align: center;
  }
  .header .logo {
    display: block;
    width: 130px;
    height: 120px;
    margin: 0 auto;
    object-fit: contain;
  }
  .header .qr {
    position: absolute;
    top: 0;
    right: 0;
    width: 110px;
    height: 110px;
  }
  .header .qr-caption {
    position: absolute;
    top: 114px;
    right: 0;
    font-size: 11px;
    text-align: right;
    white-space: nowrap;
  }
  .header .title-block {
    position: absolute;
    top: 130px;
    left: 0;
    right: 0;
    padding-right: 8px;
  }
  .uni-name {
    font-size: 35px;
    font-weight: normal;
    margin: 0;
    line-height: 1.1;
  }
  .slip-title {
    font-size: 18px;
    line-height: 1.1;
  }
  .printed-by-top {
    font-size: 13px;
    line-height: 1.3;
  }

  /* ---------- Degree ---------- */
  .degree {
    font-size: 35px;
    font-weight: normal;
    text-align: center;
    margin: 18px 0 14px 0;
    line-height: 1.2;
  }

  hr {
    border: 0;
    border-top: 1px solid #999;
    margin: 0;
    height: 0;
  }

  /* ---------- Registered courses ---------- */
  h2 {
    font-size: 24px;
    font-weight: bold;
    margin: 20px 0 20px 0;
    line-height: 1.2;
  }
  h2.courses-heading { margin-top: 22px; margin-bottom: 20px; }

  .term-line {
    font-weight: bold;
    font-size: 16px;
    margin: 0;
  }
  .term-line u { text-decoration: underline; }

  ul.courses {
    margin: 0;
    padding-left: 22px;
    font-size: 15px;
    line-height: 1.17;
    list-style-type: disc;
  }
  ul.courses li { padding-left: 0; }
  ul.courses li b { font-weight: bold; }
  ul.courses li i { font-style: italic; }

  .courses-wrap { padding-bottom: 12px; }

  /* ---------- Footer ---------- */
  .footer-rule { margin-top: 10px; }
  .footer-text {
    font-size: 18px;
    line-height: 1.15;
    margin-top: 16px;
  }
  .footer-text b { font-weight: bold; }
  .barcode-wrap {
    text-align: center;
    margin-top: 20px;
    font-size: 14px;
  }
  .barcode-wrap img {
    display: block;
    margin: 0 auto;
    width: 188px;
    height: 70px;
  }

  @media print {
    .page { width: auto; padding: 0; }
    table.fin tr { page-break-inside: avoid; }
  }
</style>
</head>
<body>
<div class="page">

  <!-- Header -->
  <div class="header">
    <img class="logo" src="{{ public_path('images/Logo.png') }}" alt="Mukuba University logo">
    <img class="qr" src="data:image/svg+xml;base64,{{ base64_encode($qrCode) }}" alt="QR code">
    <div class="qr-caption">{{ $student->id }}-{{ $printedAt->format('Y-m-d') }}-{{ $student->created_at?->format('His') ?? 'REG' }}</div>
    <div class="title-block">
      <div class="uni-name">Mukuba University</div>
      <div class="slip-title">REGISTRATION CONFIRMATION SLIP</div>
      <div class="printed-by-top">PRINTED BY {{ $student->id }} ON {{ $printedAt->format('Y-m-d') }}</div>
    </div>
  </div>

  <h1 class="degree">{{ $student->program->name }}</h1>
  <hr>

  <!-- Registered courses -->
  <h2 class="courses-heading">Registered Courses</h2>

  <div class="courses-wrap">
    <p class="term-line">This student has registered for the following courses for the term:</p>
    <ul class="courses">
      @foreach($courses as $course)
        <li><b>{{ $course->code }}</b> - <i>{{ $course->title }}</i></li>
      @endforeach
    </ul>
  </div>
  <!-- Footer -->
  <hr class="footer-rule">
  <div class="footer-text">
    PRINTED BY {{ $student->id }} ON {{ $printedAt->format('Y-m-d H:i:s') }}<br>
    This confirmation slip is printed for {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }} ({{ $student->id }}).<br>
    <b>PLEASE KEEP THIS DOCUMENT FOR FUTURE REFERENCE</b>
  </div>
  <div class="barcode-wrap">
    <img src="data:image/svg+xml;base64,{{ base64_encode($barcode) }}" alt="Barcode {{ $student->id }}">
    {{ $student->id }}
  </div>

</div>
</body>
</html>
