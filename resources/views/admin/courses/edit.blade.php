@extends('admin.programs.layout')

@section('title', 'Edit course')
@section('section', 'Courses')

@section('content')
    <p class="eyebrow"><a class="text-link" href="{{ route('admin.courses.index') }}">Courses</a> / Edit</p>
    <h1>Edit course</h1>
    <p class="intro">Update the course details.</p>

    <section class="panel">
        @include('admin.courses.form', ['course' => $course])
    </section>
@endsection
