@extends('admin.programs.layout')

@section('title', 'Edit programme')

@section('content')
    <p class="eyebrow"><a class="text-link" href="{{ route('admin.programs.index') }}">Programmes</a> / Edit</p>
    <h1>Edit programme</h1>
    <p class="intro">Update the programme name.</p>

    <section class="panel">
        @include('admin.programs.form', ['program' => $program])
    </section>
@endsection
