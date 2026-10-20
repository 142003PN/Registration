@extends('admin.programs.layout')

@section('title', $student->first_name . ' ' . $student->last_name)
@section('section', 'Students')

@section('content')
    <p class="eyebrow"><a class="text-link" href="{{ route('admin.students.index') }}">Students</a> / Details</p>
    <h1>{{ $student->first_name }} {{ $student->last_name }}</h1>
    <p class="intro">Student details.</p>

    <section class="panel">
        <dl class="detail">
            <dt>Student Id</dt>
            <dd>{{ $student->id }}</dd>
        </dl>
        <dl class="detail">
            <dt>First name</dt>
            <dd>{{ $student->first_name }}</dd>
        </dl>
        <dl class="detail">
            <dt>Middle name</dt>
            <dd>{{ $student->middle_name ?: 'Not provided' }}</dd>
        </dl>
        <dl class="detail">
            <dt>Last name</dt>
            <dd>{{ $student->last_name }}</dd>
        </dl>
        <dl class="detail">
            <dt>Programme</dt>
            <dd>{{ $student->program->name }}</dd>
        </dl>
        <div class="actions">
            <a class="button" href="{{ route('admin.students.edit', $student) }}">Edit student</a>
            <a class="button button-secondary" href="{{ route('admin.students.index') }}">Back to students</a>
            <form method="POST" action="{{ route('admin.students.destroy', $student) }}" onsubmit="return confirm('Delete this student?')">
                @csrf
                @method('DELETE')
                <button class="button button-danger" type="submit">Delete student</button>
            </form>
        </div>
    </section>
@endsection
