@extends('admin.programs.layout')

@section('title', 'Edit student')
@section('section', 'Students')

@section('content')
    <p class="eyebrow"><a class="text-link" href="{{ route('admin.students.index') }}">Students</a> / Edit</p>
    <h1>Edit student</h1>
    <p class="intro">Update the student's details and assigned programme.</p>

    <section class="panel">
        @include('admin.students.form', ['student' => $student])
    </section>
@endsection
