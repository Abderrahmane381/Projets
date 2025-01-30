@extends('layouts.app')

@section('title', 'Edit Theme')

@section('content')
<div class="container">
    <header class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Theme</h1>
        <a href="{{ route('editor.themes.index') }}" class="btn btn-secondary">Back to Themes</a>
    </header>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('editor.themes.update', $theme->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Theme Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $theme->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="4" required>{{ old('description', $theme->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="responsible_id" class="form-label">Theme Responsible</label>
                    <select class="form-select @error('responsible_id') is-invalid @enderror" 
                            id="responsible_id" name="responsible_id" required>
                        <option value="">Select Theme Responsible</option>
                        @foreach(\App\Models\User::role('theme_responsible')->get() as $user)
                            <option value="{{ $user->id }}" 
                                {{ old('responsible_id', $theme->responsible_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('responsible_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                           {{ old('is_active', $theme->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>

                <button type="submit" class="btn btn-primary">Update Theme</button>
            </form>
        </div>
    </div>
</div>
@endsection
