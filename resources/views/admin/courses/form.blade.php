<form method="POST" action="{{ $course ? route('admin.courses.update', $course) : route('admin.courses.store') }}">
    @csrf
    @if ($course)
        @method('PUT')
    @endif

    <div class="field">
        <label for="code">Course code</label>
        <input id="code" type="text" name="code" value="{{ old('code', $course?->code) }}" maxlength="10" required autofocus>
        <p class="hint">Course codes must be unique within each programme (up to 10 characters).</p>
        @error('code')
            <p class="error" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <div class="field">
        <label for="title">Course title</label>
        <input id="title" type="text" name="title" value="{{ old('title', $course?->title) }}" maxlength="100" required>
        @error('title')
            <p class="error" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <div class="field">
        <label for="program_id">Programme</label>
        <select id="program_id" name="program_id" required>
            <option value="">Select a programme</option>
            @foreach ($programs as $program)
                <option value="{{ $program->id }}" @selected((string) old('program_id', $course?->program_id) === (string) $program->id)>
                    {{ $program->name }}
                </option>
            @endforeach
        </select>
        @error('program_id')
            <p class="error" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <div class="actions">
        <button class="button" type="submit">{{ $course ? 'Save changes' : 'Create course' }}</button>
        <a class="button button-secondary" href="{{ $course ? route('admin.courses.show', $course) : route('admin.courses.index') }}">Cancel</a>
    </div>
</form>
