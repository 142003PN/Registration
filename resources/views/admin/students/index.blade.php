@extends('admin.programs.layout')

@section('title', 'Manage students')
@section('section', 'Students')

@section('content')
    <div class="catalog-page-header">
        <div class="catalog-heading"><p class="eyebrow">Registration records</p><h1>Students</h1></div>
        <div class="catalog-page-actions"><a class="button" href="{{ route('admin.students.create') }}">Add student</a></div>
    </div>

    <section class="panel catalog-card" aria-labelledby="students-heading">
        <div class="catalog-card-heading"><h2 id="students-heading">All students</h2><span class="programme-count">{{ $students->count() }}</span></div>
        <div class="catalog-card-body">

        @if ($students->isEmpty())
            <p class="empty">No students have been added yet.</p>
        @else
            @php
                $tableId = 'students-table';
                $recordLabel = 'students';
                $records = $students;
                $tableFilters = [['key' => 'programme', 'label' => 'Programme', 'path' => 'program.name']];
            @endphp
            @include('partials.index-table-controls', compact('tableId', 'recordLabel', 'records', 'tableFilters'))
            <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th scope="col">Student</th>
                        <th scope="col">Programme</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody id="{{ $tableId }}">
                    @foreach ($students as $student)
                        <tr @include('partials.index-row-filters', ['record' => $student, 'tableFilters' => $tableFilters])>
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
            </div>
            @include('partials.jquery-pagination', ['targetSelector' => '#' . $tableId . ' > tr[data-record]', 'label' => 'students'])
        @endif
        </div>
    </section>
@endsection
