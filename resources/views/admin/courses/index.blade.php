@extends('admin.programs.layout')

@section('title', 'Manage courses')
@section('section', 'Courses')

@section('content')
    <p class="eyebrow">Administrator dashboard</p>
    <h1>Courses</h1>
    <p class="intro">Create and manage courses offered by each programme.</p>

    <section class="panel" aria-labelledby="courses-heading">
        <div class="toolbar">
            <h2 id="courses-heading">All courses</h2>
            <a class="button" href="{{ route('admin.courses.create') }}">Add course</a>
        </div>

        <form class="import-form" method="POST" action="{{ route('admin.courses.import') }}" enctype="multipart/form-data">
            @csrf
            <div class="field">
                <label for="course-file">Import courses from Excel</label>
                <input id="course-file" name="file" type="file" accept=".xlsx,.xls,.csv" required>
                <p class="hint">Upload an .xlsx, .xls, or .csv file with Programme Name, Course Code, and Course Title columns, followed by one course per row. Programme names must match an existing programme.</p>
                @foreach ($errors->get('file') as $error)
                    <p class="error">{{ $error }}</p>
                @endforeach
            </div>
            <button class="button" type="submit">Import spreadsheet</button>
        </form>

        @if ($courses->isEmpty())
            <p class="empty">No courses have been added yet.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th scope="col">Code</th>
                        <th scope="col">Course</th>
                        <th scope="col">Programme</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($courses as $course)
                        <tr>
                            <td>{{ $course->code }}</td>
                            <td>{{ $course->title }}</td>
                            <td>{{ $course->program?->name ?? 'Unassigned' }}</td>
                            <td>
                                <div class="actions">
                                    <a class="text-link" href="{{ route('admin.courses.show', $course) }}">View</a>
                                    <a class="text-link" href="{{ route('admin.courses.edit', $course) }}">Edit</a>
                                    <form class="inline-form" method="POST" action="{{ route('admin.courses.destroy', $course) }}" onsubmit="return confirm('Delete this course?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination">{{ $courses->links() }}</div>
        @endif
    </section>
@endsection
