@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Employee Database</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('employees.create') }}" class="btn btn-primary">
                <i class="entypo-plus"></i> Add New Cleaner
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover data-table">
                    <thead>
                        <tr>
                            <th>Employee No.</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Phone</th>
                            <th>Hire Date</th>
                            <th>Rating</th>
                            <th>Photo Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                        <tr>
                            <td>
                                @if($employee->employee_no)
                                    {{ $employee->employee_no }}
                                @else
                                    <span class="text-muted">Not assigned</span>
                                @endif
                            </td>
                            <td>{{ $employee->name }}</td>
                            <td>
                                @if($employee->position)
                                    {{ $employee->position }}
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </td>
                            <td>{{ $employee->phone }}</td>
                            <td>{{ $employee->hire_date->format('M d, Y') }}</td>
                            <td>
                                @if($employee->ratings_avg_rating)
                                    {{ number_format($employee->ratings_avg_rating, 1) }} / 5.0
                                @else
                                    No ratings yet
                                @endif
                            </td>
                            <td>
                                @if($employee->hasPhotos())
                                    <span class="badge bg-success">
                                        <i class="entypo-check"></i> Approved
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="entypo-camera"></i> No Photos
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('employees.show', $employee->id) }}"
                                       class="btn btn-info rounded-circle"
                                       data-tooltip="View">
                                        <i class="entypo-eye"></i>
                                    </a>
                                    <a href="{{ route('employees.edit', $employee->id) }}"
                                       class="btn btn-warning rounded-circle"
                                       data-tooltip="Edit">
                                        <i class="entypo-pencil"></i>
                                    </a>
                                    <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-danger rounded-circle"
                                                data-tooltip="Delete"
                                                data-action="delete-employee"
                                                data-employee-name="{{ $employee->name }}">
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
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="confirmDeleteLabel">Confirm Deletion</h4>
                </div>
                <div class="modal-body">
                    <p id="confirmDeleteMessage">Are you sure you want to delete this employee? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
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
@endpush

@push('scripts')
<script>
(function(){
    var formToSubmit = null;
    $(document).on('click', 'button[data-action="delete-employee"]', function(e){
        e.preventDefault();
        formToSubmit = $(this).closest('form');
        var name = $(this).data('employee-name');
        var msg = 'Are you sure you want to delete this employee? This action cannot be undone.';
        if (name) {
            msg = 'Are you sure you want to delete \'' + name + '\'? This action cannot be undone.';
        }
        $('#confirmDeleteMessage').text(msg);
        $('#confirmDeleteModal').modal('show');
    });

    $('#confirmDeleteBtn').on('click', function(){
        if (formToSubmit) {
            formToSubmit.trigger('submit');
            formToSubmit = null;
        }
        $('#confirmDeleteModal').modal('hide');
    });
})();
</script>
@endpush
