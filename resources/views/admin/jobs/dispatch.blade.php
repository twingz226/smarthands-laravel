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
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Job Dispatch</h1>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h6>Pending Jobs</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover data-table">
                            <thead>
                                <tr>
                                    <th>Job ID</th>
                                    <th>Customer</th>
                                    <th>Service</th>
                                    <th>Scheduled Date</th>
                                    <th>Address</th>
                                    <th>Assign Cleaner</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingJobs as $job)
                                <tr>
                                    <td>#{{ $job->id }}</td>
                                    <td>{{ $job->customer->name }}</td>
                                    <td>{{ $job->service->name }}</td>
                                    <td>{{ $job->scheduled_date->format('M d, Y H:i') }}</td>
                                    <td>{{ Str::limit($job->address, 30) }}</td>
                                    <td>
                                        <form action="{{ route('jobs.assign', $job->id) }}" method="POST">
                                            @csrf
                                            <div class="input-group">
                                                <select name="employee_id" class="form-select form-select-sm" required>
                                                    <option value="">Select Cleaner</option>
                                                    @foreach($availableCleaners as $cleaner)
                                                    <option value="{{ $cleaner->id }}">{{ $cleaner->name }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-primary">Assign</button>
                                            </div>
                                        </form>
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
@include('admin.partials.scripts')