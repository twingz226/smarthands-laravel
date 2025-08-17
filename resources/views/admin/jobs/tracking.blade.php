@include('admin.partials.header')
@include('admin.partials.circular-buttons')

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Job Tracking & Assignments</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover data-table">
                    <thead class="table-dark">
                        <tr>
                            <th>Job ID</th>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Assigned To</th>
                            <th>Status</th>
                            <th>Scheduled Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobs as $job)
                            <tr>
                                <td>#{{ $job->id }}</td>
                                <td>{{ $job->customer->name }}</td>
                                <td>{{ $job->service->name }}</td>
                                <td>
                                    @if($job->employees->count() > 0)
                                        @foreach($job->employees as $employee)
                                            <span class="badge bg-info">{{ $employee->name }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-warning">Unassigned</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $job->status === 'pending' ? 'warning' : ($job->status === 'assigned' ? 'primary' : ($job->status === 'in_progress' ? 'info' : 'success')) }}">
                                        {{ ucfirst($job->status) }}
                                    </span>
                                </td>
                                <td>{{ $job->scheduled_date->format('M d, Y H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @if($job->status === 'pending')
                                            <button type="button" 
                                                    class="btn btn-primary rounded-circle" 
                                                    data-toggle="modal" 
                                                    data-target="#assignModal{{ $job->id }}"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    title="Assign Cleaners">
                                                <i class="entypo-user-add"></i>
                                            </button>
                                        @endif
                                        
                                        @if(in_array($job->status, ['assigned', 'in_progress']))
                                            <form action="{{ route('jobs.update-status', $job) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="{{ $job->status === 'assigned' ? 'in_progress' : 'completed' }}">
                                                <button type="submit" 
                                                        class="btn {{ $job->status === 'assigned' ? 'btn-success' : 'btn-primary' }} rounded-circle"
                                                        data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="{{ $job->status === 'assigned' ? 'Start Job' : 'Mark as Complete' }}">
                                                    <i class="entypo-{{ $job->status === 'assigned' ? 'play' : 'check' }}"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <a href="{{ route('jobs.show', $job->id) }}" 
                                           class="btn btn-info rounded-circle"
                                           data-toggle="tooltip"
                                           data-placement="top"
                                           title="View Details">
                                            <i class="entypo-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <!-- Assignment Modal -->
                            @if($job->status === 'pending')
                                <div class="modal fade" id="assignModal{{ $job->id }}" tabindex="-1" role="dialog" aria-labelledby="assignModalLabel{{ $job->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('jobs.assign', $job) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="assignModalLabel{{ $job->id }}">
                                                        <i class="entypo-users"></i> Assign Cleaners to Job #{{ $job->id }}
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">
                                                            <i class="entypo-user"></i> Select Cleaners
                                                        </label>
                                                        <div class="cleaner-list" style="max-height: 300px; overflow-y: auto;">
                                                            @foreach($availableEmployees ?? [] as $employee)
                                                                <div class="form-check mb-2">
                                                                    <input type="checkbox" 
                                                                           class="form-check-input" 
                                                                           name="employee_ids[]" 
                                                                           value="{{ $employee->id }}" 
                                                                           id="employee{{ $job->id }}_{{ $employee->id }}">
                                                                    <label class="form-check-label" for="employee{{ $job->id }}_{{ $employee->id }}">
                                                                        {{ $employee->name }} - {{ $employee->phone }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="form-text text-muted mt-2">
                                                            <i class="entypo-info-circled"></i> 
                                                            Check the boxes to select multiple cleaners
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                        <i class="entypo-cancel"></i> Cancel
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="entypo-check"></i> Assign Cleaners
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No jobs found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.partials.scripts')

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('.data-table').DataTable({
            "order": [[0, "desc"]]
        });

        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();
        
        // Also initialize any elements with title attribute
        $('[title]').each(function() {
            $(this).attr('data-toggle', 'tooltip');
            $(this).tooltip();
        });
    });
</script> 