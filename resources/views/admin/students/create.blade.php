@extends('admin.programs.layout')

@section('title', 'Add student')
@section('section', 'Students')

@section('content')
    <p class="eyebrow"><a class="text-link" href="{{ route('admin.students.index') }}">Students</a> / Add</p>
    <h1>Add a student</h1>
    <p class="intro">Enter the student's name and select one programme.</p>

    <section class="panel">
        @include('admin.students.form', ['student' => null])
    </section>
@endsection
