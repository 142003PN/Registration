<form method="POST" action="{{ $student ? route('admin.students.update', $student) : route('admin.students.store') }}">
    @csrf
    @if ($student)
        @method('PUT')
    @endif

    <div class="field">
        <label for="first_name">First name</label>
        <input id="first_name" type="text" name="first_name" value="{{ old('first_name', $student?->first_name) }}" maxlength="15" required autofocus>
        @error('first_name')
            <p class="error" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <div class="field">
        <label for="middle_name">Middle name (optional)</label>
        <input id="middle_name" type="text" name="middle_name" value="{{ old('middle_name', $student?->middle_name) }}" maxlength="15">
        @error('middle_name')
            <p class="error" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <div class="field">
        <label for="last_name">Last name</label>
        <input id="last_name" type="text" name="last_name" value="{{ old('last_name', $student?->last_name) }}" maxlength="15" required>
        @error('last_name')
            <p class="error" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <div class="field">
        <label for="program_id">Programme</label>
        <select id="program_id" name="program_id" required>
            <option value="">Select a programme</option>
            @foreach ($programs as $program)
                <option value="{{ $program->id }}" @selected((string) old('program_id', $student?->program_id) === (string) $program->id)>
                    {{ $program->name }}
                </option>
            @endforeach
        </select>
        @error('program_id')
            <p class="error" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <div class="actions">
        <button class="button" type="submit">{{ $student ? 'Save changes' : 'Create student' }}</button>
        <a class="button button-secondary" href="{{ $student ? route('admin.students.show', $student) : route('admin.students.index') }}">Cancel</a>
    </div>
</form>
