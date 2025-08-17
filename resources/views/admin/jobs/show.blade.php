@include('admin.partials.header')

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Job Details #{{ $job->id }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('jobs.tracking') }}" class="btn btn-sm btn-secondary">
                <i class="entypo-back"></i> Back to Job Tracking
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Status:</strong>
                            <span class="badge 
                                @if($job->status == 'assigned') badge-primary
                                @elseif($job->status == 'in_progress') badge-warning
                                @elseif($job->status == 'completed') badge-success
                                @else badge-secondary
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                            </span>
                        </div>
                        <div class="col-md-4">
                            <strong>Scheduled Date:</strong><br>
                            {{ $job->scheduled_date->format('M d, Y H:i') }}
                        </div>
                        <div class="col-md-4">
                            <strong>Created:</strong><br>
                            {{ $job->created_at->format('M d, Y H:i') }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Customer:</strong><br>
                            {{ $job->customer->name }}<br>
                            {{ $job->customer->email }}<br>
                            {{ $job->customer->contact }}
                        </div>
                        <div class="col-md-6">
                            <strong>Service:</strong><br>
                            {{ $job->service->name }}<br>
                            Price: ₱{{ number_format($job->service->price, 2) }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Address:</strong><br>
                            {{ $job->address }}
                        </div>
                    </div>

                    @if($job->special_instructions)
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Special Instructions:</strong><br>
                            {{ $job->special_instructions }}
                        </div>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Assigned Cleaners:</strong><br>
                            @if($job->employees->count() > 0)
                                @foreach($job->employees as $employee)
                                    <div class="mb-2">
                                        <span class="badge bg-info">{{ $employee->name }}</span><br>
                                        <small class="text-muted">
                                            {{ $employee->email }}<br>
                                            {{ $employee->phone }}
                                        </small>
                                    </div>
                                @endforeach
                            @else
                                <span class="text-danger">Not assigned</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <strong>Timeline:</strong><br>
                            @if($job->assigned_at)
                                Assigned: {{ $job->assigned_at->format('M d, Y H:i') }}<br>
                            @endif
                            @if($job->started_at)
                                Started: {{ $job->started_at->format('M d, Y H:i') }}<br>
                            @endif
                            @if($job->completed_at)
                                Completed: {{ $job->completed_at->format('M d, Y H:i') }}
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <div class="btn-group">
                                @if(in_array($job->status, ['assigned', 'in_progress']))
                                    <button class="btn btn-info" data-toggle="modal" data-target="#reassignModal">
                                        <i class="fas fa-user-edit"></i> Reassign
                                    </button>
                                    <form action="{{ route('jobs.update-status', $job->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        @if($job->status == 'assigned')
                                            <input type="hidden" name="status" value="in_progress">
                                            <button type="submit" class="btn btn-warning">
                                                <i class="fas fa-play"></i> Start Job
                                            </button>
                                        @elseif($job->status == 'in_progress')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check"></i> Complete Job
                                            </button>
                                        @endif
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(in_array($job->status, ['assigned', 'in_progress']))
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
            <form action="{{ route('jobs.reassign', $job->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Current Cleaners</label>
                        <div class="mb-3">
                            @foreach($job->employees as $employee)
                                <span class="badge bg-info">{{ $employee->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label>New Cleaners</label>
                        <select class="form-control" name="employee_ids[]" multiple required>
                            <option value="">-- Select Cleaners --</option>
                            @foreach($availableEmployees ?? [] as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple cleaners</small>
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
@endif

@include('admin.partials.scripts')