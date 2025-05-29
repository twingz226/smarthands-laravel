@include('admin.partials.header')

<div class="row">
    <div class="col-sm-12">
        <div class="well">
            <div class="container-fluid py-4 px-5">
                <h3>Welcome to <strong> Smarthands Cleaning Service Management System</strong></h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Checklists</h5>
        <a href="{{ route('checklists.index') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add New
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($checklists as $checklist)
                    <tr>
                        <td>{{ $checklist->name }}</td>
                        <td>{{ Str::limit($checklist->description, 50) }}</td>
                        <td>
                            <span class="badge badge-{{ $checklist->is_active ? 'success' : 'danger' }}">
                                {{ $checklist->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('checklists.edit', $checklist->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('checklists.destroy', $checklist->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $checklists->links() }}
        </div>
    </div>
</div>

@include('admin.partials.scripts')