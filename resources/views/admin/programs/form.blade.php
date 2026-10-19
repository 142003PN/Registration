<form method="POST" action="{{ $program ? route('admin.programs.update', $program) : route('admin.programs.store') }}">
    @csrf
    @if ($program)
        @method('PUT')
    @endif

    <div class="field">
        <label for="name">Programme name</label>
        <input id="name" type="text" name="name" value="{{ old('name', $program?->name) }}" maxlength="50" required autofocus>
        <p class="hint">Use a unique name of up to 50 characters.</p>
        @error('name')
            <p class="error" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <div class="actions">
        <button class="button" type="submit">{{ $program ? 'Save changes' : 'Create programme' }}</button>
        <a class="button button-secondary" href="{{ $program ? route('admin.programs.show', $program) : route('admin.programs.index') }}">Cancel</a>
    </div>
</form>
