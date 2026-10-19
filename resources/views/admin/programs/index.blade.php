@extends('admin.programs.layout')

@section('title', 'Manage programmes')

@section('content')
    <p class="eyebrow">Administrator dashboard</p>
    <h1>Programmes</h1>
    <p class="intro">Create and manage the programmes available in the registration system.</p>

    <section class="panel" aria-labelledby="programmes-heading">
        <div class="toolbar">
            <div class="table-heading">
                <h2 id="programmes-heading">All programmes</h2>
                <span class="programme-count">{{ $programs->total() }}</span>
            </div>
            <div class="actions">
                <a class="button button-secondary" href="{{ route('admin.programs.create') }}">Add programme</a>
            </div>
        </div>

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
            <div class="table-wrap">
                <table class="programmes-table">
                    <thead>
                        <tr>
                            <th scope="col">Programme</th>
                            <th scope="col" class="actions-heading">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($programs as $program)
                            <tr>
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
            <div class="pagination">{{ $programs->links() }}</div>
        @endif
    </section>
@endsection
