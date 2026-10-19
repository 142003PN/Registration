@extends('admin.programs.layout')

@section('title', $program->name)

@section('content')
    <p class="eyebrow"><a class="text-link" href="{{ route('admin.programs.index') }}">Programmes</a> / Details</p>
    <h1>{{ $program->name }}</h1>
    <p class="intro">Programme details.</p>

    <section class="panel">
        <dl class="detail">
            <dt>Programme name</dt>
            <dd>{{ $program->name }}</dd>
        </dl>
        <div class="actions">
            <a class="button" href="{{ route('admin.programs.edit', $program) }}">Edit programme</a>
            <a class="button button-secondary" href="{{ route('admin.programs.index') }}">Back to programmes</a>
            <form method="POST" action="{{ route('admin.programs.destroy', $program) }}" onsubmit="return confirm('Delete this programme?')">
                @csrf
                @method('DELETE')
                <button class="button button-danger" type="submit">Delete programme</button>
            </form>
        </div>
    </section>
@endsection
