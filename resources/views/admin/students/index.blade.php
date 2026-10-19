@extends('admin.programs.layout')

@section('title', 'Manage students')
@section('section', 'Students')

@section('content')
    <p class="eyebrow">Administrator dashboard</p>
    <h1>Students</h1>
    <p class="intro">Create and manage students, each assigned to one programme.</p>

    <section class="panel" aria-labelledby="students-heading">
        <div class="toolbar">
            <h2 id="students-heading">All students</h2>
            <a class="button" href="{{ route('admin.students.create') }}">Add student</a>
        </div>

        @if ($students->isEmpty())
            <p class="empty">No students have been added yet.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th scope="col">Student</th>
                        <th scope="col">Programme</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr>
                            <td>{{ $student->first_name }} @if ($student->middle_name){{ $student->middle_name }} @endif{{ $student->last_name }}</td>
                            <td>{{ $student->program->name }}</td>
                            <td>
                                <div class="actions">
                                    <a class="text-link" href="{{ route('admin.students.show', $student) }}">View</a>
                                    <a class="text-link" href="{{ route('admin.students.edit', $student) }}">Edit</a>
                                    <form class="inline-form" method="POST" action="{{ route('admin.students.destroy', $student) }}" onsubmit="return confirm('Delete this student?')">
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
            <div class="pagination">{{ $students->links() }}</div>
        @endif
    </section>
@endsection
