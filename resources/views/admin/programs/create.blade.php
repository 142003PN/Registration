@extends('admin.programs.layout')

@section('title', 'Add programme')

@section('content')
    <p class="eyebrow"><a class="text-link" href="{{ route('admin.programs.index') }}">Programmes</a> / Add</p>
    <h1>Add a programme</h1>
    <p class="intro">Enter a unique name for the new programme.</p>

    <section class="panel">
        @include('admin.programs.form', ['program' => null])
    </section>
@endsection
