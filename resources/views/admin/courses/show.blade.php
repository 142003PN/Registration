@extends('admin.programs.layout')

@section('title', $course->code)
@section('section', 'Courses')

@section('content')
    <p class="eyebrow"><a class="text-link" href="{{ route('admin.courses.index') }}">Courses</a> / Details</p>
    <h1>{{ $course->title }}</h1>
    <p class="intro">Course details.</p>

    <section class="panel">
        <dl class="detail">
            <dt>Course code</dt>
            <dd>{{ $course->code }}</dd>
        </dl>
        <dl class="detail">
            <dt>Course title</dt>
            <dd>{{ $course->title }}</dd>
        </dl>
        <dl class="detail">
            <dt>Programme</dt>
            <dd>{{ $course->program?->name ?? 'Unassigned' }}</dd>
        </dl>
        <div class="actions">
            <a class="button" href="{{ route('admin.courses.edit', $course) }}">Edit course</a>
            <a class="button button-secondary" href="{{ route('admin.courses.index') }}">Back to courses</a>
            <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" onsubmit="return confirm('Delete this course?')">
                @csrf
                @method('DELETE')
                <button class="button button-danger" type="submit">Delete course</button>
            </form>
        </div>
    </section>
@endsection
