@extends('admin.programs.layout')

@section('title', 'Add course')
@section('section', 'Courses')

@section('content')
    <p class="eyebrow"><a class="text-link" href="{{ route('admin.courses.index') }}">Courses</a> / Add</p>
    <h1>Add a course</h1>
    <p class="intro">Enter a course code, title, and programme.</p>

    <section class="panel">
        @include('admin.courses.form', ['course' => null])
    </section>
@endsection
