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

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Active Job Assignments</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Job ID</th>
                                    <th>Customer</th>
                                    <th>Service</th>
                                    <th>Assigned Cleaner</th>
                                    <th>Scheduled Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activeJobs as $job)
                                <tr>
                                    <td>#{{ $job->id }}</td>
                                    <td>{{ $job->customer->name }}</td>
                                    <td>{{ $job->service->name }}</td>
                                    <td>
                                        @if($job->employee)
                                            {{ $job->employee->name }}
                                        @else
                                            <span class="text-danger">Unassigned</span>
                                        @endif
                                    </td>
                                    <td>{{ $job->scheduled_date->format('M d, Y H:i') }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($job->status == 'assigned') badge-primary
                                            @elseif($job->status == 'in_progress') badge-warning
                                            @elseif($job->status == 'completed') badge-success
                                            @else badge-secondary
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info" data-toggle="modal" 
                                                data-target="#reassignModal" 
                                                data-job-id="{{ $job->id }}"
                                                data-current-employee="{{ $job->employee ? $job->employee->name : 'Unassigned' }}"
                                                data-employee-id="{{ $job->employee_id }}">
                                                <i class="fas fa-user-edit"></i> Reassign
                                            </button>
                                            <a href="{{ route('jobs.show', $job->id) }}" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reassign Modal -->
<div class="modal fade" id="reassignModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reassign Job</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="reassignForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="job_id" id="modalJobId">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Current Cleaner</label>
                        <input type="text" class="form-control" id="currentEmployeeName" readonly>
                    </div>
                    <div class="form-group">
                        <label>New Cleaner</label>
                        <select class="form-control" name="employee_id" required>
                            <option value="">-- Select Cleaner --</option>
                            @foreach($availableEmployees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Reassign</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
$(document).ready(function() {
    $('#reassignModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var jobId = button.data('job-id');
        var currentEmployee = button.data('current-employee');
        
        var modal = $(this);
        modal.find('#currentEmployeeName').val(currentEmployee);
        modal.find('#modalJobId').val(jobId);
        modal.find('#reassignForm').attr('action', '/admin/jobs/' + jobId + '/reassign');
    });
});
</script>

@endsection
@include('admin.partials.scripts')