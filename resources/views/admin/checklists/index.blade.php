@include('admin.partials.header')

<style>
.btn.rounded-circle {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin: 0 2px;
    position: relative;
    border-radius: 50% !important;
    transition: all 0.3s ease;
}

.btn.rounded-circle:hover {
    transform: scale(1.1);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.btn.rounded-circle i {
    margin: 0;
    font-size: 14px;
}

[data-tooltip] {
    position: relative;
}

[data-tooltip]:before {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    padding: 4px 8px;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    border-radius: 4px;
    font-size: 12px;
    white-space: nowrap;
    visibility: hidden;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 1000;
}

[data-tooltip]:hover:before {
    visibility: visible;
    opacity: 1;
}
</style>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Checklists</h5>
        <a href="{{ route('checklists.add') }}" class="btn btn-primary btn-sm">
            <i class="entypo-plus"></i> Add New
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
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('checklists.edit', $checklist->id) }}" 
                                   class="btn btn-warning rounded-circle" 
                                   data-tooltip="Edit">
                                    <i class="entypo-pencil"></i>
                                </a>
                                <form action="{{ route('checklists.destroy', $checklist->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-danger rounded-circle" 
                                            data-tooltip="Delete"
                                            onclick="return confirm('Are you sure?')">
                                        <i class="entypo-trash"></i>
                                    </button>
                                </form>
                            </div>
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