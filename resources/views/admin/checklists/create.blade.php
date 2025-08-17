@include('admin.partials.header')

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Create New Checklist</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('checklists.store') }}" method="POST">
            @csrf
            
            <div class="form-group mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" 
                       class="form-control @error('name') is-invalid @enderror" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}" 
                       required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" 
                          name="description" 
                          rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <div class="form-check">
                    <input type="checkbox" 
                           class="form-check-input @error('is_active') is-invalid @enderror" 
                           id="is_active" 
                           name="is_active" 
                           value="1" 
                           {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                    @error('is_active')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Create Checklist</button>
                <a href="{{ route('checklists.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@include('admin.partials.scripts') 