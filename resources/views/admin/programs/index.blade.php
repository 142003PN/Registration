@extends('admin.programs.layout')

@section('title', 'Manage programmes')

@section('content')
    <p class="eyebrow">Administrator dashboard</p>
    <h1>Programmes</h1>
    <p class="intro">Create and manage the programmes available in the registration system.</p>

    <section class="panel" aria-labelledby="programmes-heading">
        <div class="toolbar">
            <h2 id="programmes-heading">All programmes</h2>
            <a class="button" href="{{ route('admin.programs.create') }}">Add programme</a>
        </div>

        @if ($programs->isEmpty())
            <p class="empty">No programmes have been added yet.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th scope="col">Programme</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($programs as $program)
                        <tr>
                            <td>{{ $program->name }}</td>
                            <td>
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
            <div class="pagination">{{ $programs->links() }}</div>
        @endif
    </section>
@endsection
