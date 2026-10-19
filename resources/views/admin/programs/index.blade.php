@extends('admin.programs.layout')

@section('title', 'Manage programmes')

@section('content')
    <div class="catalog-page-header">
        <div class="catalog-heading"><p class="eyebrow">Registration records</p><h1>Programmes</h1></div>
        <div class="catalog-page-actions"><a class="button button-secondary" href="{{ route('admin.programs.create') }}">Add programme</a></div>
    </div>

    <section class="panel catalog-card" aria-labelledby="programmes-heading">
        <div class="catalog-card-heading"><h2 id="programmes-heading">All programmes</h2><span class="programme-count">{{ $programs->count() }}</span></div>
        <div class="catalog-card-body">

        <form class="import-form" method="POST" action="{{ route('admin.programs.import') }}" enctype="multipart/form-data">
            @csrf
            <div class="field">
                <label for="file">Import programmes from Excel</label>
                <input id="file" name="file" type="file" accept=".xlsx,.xls,.csv" required>
                <p class="hint">Upload an .xlsx, .xls, or .csv file. Use the first column with a header of “Programme Name” and one programme per row. Names must be no longer than 50 characters.</p>
                @foreach ($errors->get('file') as $error)
                    <p class="error">{{ $error }}</p>
                @endforeach
            </div>
            <button class="button" type="submit">Import spreadsheet</button>
        </form>

        @if ($programs->isEmpty())
            <p class="empty">No programmes have been added yet.</p>
        @else
            @php
                $tableId = 'programmes-table';
                $recordLabel = 'programmes';
                $records = $programs;
                $tableFilters = [];
            @endphp
            @include('partials.index-table-controls', compact('tableId', 'recordLabel', 'records', 'tableFilters'))
            <div class="table-wrap">
                <table class="programmes-table data-table">
                    <thead>
                        <tr>
                            <th scope="col">Programme</th>
                            <th scope="col" class="actions-heading">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="{{ $tableId }}">
                        @foreach ($programs as $program)
                            <tr @include('partials.index-row-filters', ['record' => $program, 'tableFilters' => $tableFilters])>
                                <td class="programme-name">
                                    <span class="programme-marker" aria-hidden="true"></span>
                                    {{ $program->name }}
                                </td>
                                <td class="programme-actions">
                                    <div class="actions">
                                        <a class="text-link" href="{{ route('admin.programs.show', $program) }}">View</a>
                                        <a class="text-link" href="{{ route('admin.programs.edit', $program) }}">Edit</a>
                                        <form class="inline-form" method="POST" action="{{ route('admin.programs.destroy', $program) }}" onsubmit="return confirm('Delete this programme?')">
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
            @include('partials.jquery-pagination', ['targetSelector' => '#' . $tableId . ' > tr[data-record]', 'label' => 'programmes'])
        @endif
        </div>
    </section>
@endsection
