@extends('admin.programs.layout')

@section('title', 'Manage students')
@section('section', 'Students')

@section('content')
    <div class="catalog-page-header">
        <div class="catalog-heading"><p class="eyebrow">Registration records</p><h1>Students</h1></div>
        <div class="catalog-page-actions">
            <a class="button button-secondary" href="{{ route('admin.students.export') }}">Export Excel</a>
            <a class="button" href="{{ route('admin.students.create') }}">Add student</a>
        </div>
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
                        <th scope="col">Student Id</th>
                        <th scope="col">Student Name</th>
                        <th scope="col">Programme</th>
                    </tr>
                </thead>
                <tbody id="{{ $tableId }}">
                    @foreach ($students as $student)
                        <tr style="cursor: pointer" onclick="window.location.href='{{ route('admin.students.show', $student) }}'" @include('partials.index-row-filters', ['record' => $student, 'tableFilters' => $tableFilters])>
                            <td>{{ $student->id}}</td>
                            <td>{{ $student->first_name }} @if ($student->middle_name){{ $student->middle_name }} @endif{{ $student->last_name }}</td>
                            <td>{{ $student->program->name }}</td>
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
