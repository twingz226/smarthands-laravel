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
                    <h6 class="m-0 font-weight-bold text-primary">Job Tracking</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" 
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" 
                             aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="#"><i class="fas fa-sync-alt fa-sm mr-2"></i>Refresh</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-file-export fa-sm mr-2"></i>Export</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status-filter">Filter by Status:</label>
                                <select class="form-control" id="status-filter">
                                    <option value="all">All Jobs</option>
                                    <option value="assigned">Assigned</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date-filter">Filter by Date:</label>
                                <input type="date" class="form-control" id="date-filter">
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-end justify-content-end">
                            <button class="btn btn-primary mr-2">
                                <i class="fas fa-filter mr-2"></i>Apply Filters
                            </button>
                            <button class="btn btn-secondary">
                                <i class="fas fa-redo mr-2"></i>Reset
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="jobTrackingTable" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Job ID</th>
                                    <th>Customer</th>
                                    <th>Service Type</th>
                                    <th>Assigned To</th>
                                    <th>Status</th>
                                    <th>Scheduled Date</th>
                                    <th>Start Time</th>
                                    <th>Completion Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jobs as $job)
                                <tr>
                                    <td>#{{ $job->id }}</td>
                                    <td>{{ $job->customer->name ?? 'N/A' }}</td>
                                    <td>{{ $job->service_type }}</td>
                                    <td>{{ $job->employee->name ?? 'Unassigned' }}</td>
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
                                    <td>{{ $job->scheduled_date->format('M d, Y') }}</td>
                                    <td>{{ $job->start_time ? \Carbon\Carbon::parse($job->start_time)->format('h:i A') : '--' }}</td>
                                    <td>{{ $job->completion_time ? \Carbon\Carbon::parse($job->completion_time)->format('h:i A') : '--' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Track Location">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-success" data-toggle="tooltip" title="Mark Complete">
                                                <i class="fas fa-check"></i>
                                            </button>
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

<!-- Map Modal -->
<div class="modal fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="locationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="locationModalLabel">Job Location Tracking</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="jobLocationMap" style="height: 400px; width: 100%;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


@section('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#jobTrackingTable').DataTable({
        responsive: true,
        order: [[5, 'asc']] // Default sort by scheduled date
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Location tracking modal
    $('.btn-map').click(function() {
        var jobId = $(this).data('job-id');
        var address = $(this).data('address');
        
        $('#locationModalLabel').text('Tracking Job #' + jobId);
        $('#locationModal').modal('show');
        
        // Here you would initialize your map with the address
        // For example using Google Maps API:
        // initMap(address);
    });

    // Status filter
    $('#status-filter').change(function() {
        var status = $(this).val();
        var table = $('#jobTrackingTable').DataTable();
        
        if (status === 'all') {
            table.search('').columns().search('').draw();
        } else {
            table.column(4).search(status).draw();
        }
    });

    // Date filter
    $('#date-filter').change(function() {
        var date = $(this).val();
        var table = $('#jobTrackingTable').DataTable();
        
        if (date) {
            table.column(5).search(date).draw();
        } else {
            table.column(5).search('').draw();
        }
    });
});

// Example map initialization function
function initMap(address) {
    // This would be replaced with actual Google Maps API implementation
    console.log("Initializing map for address: " + address);
    // var map = new google.maps.Map(document.getElementById('jobLocationMap'), {...});
    // var geocoder = new google.maps.Geocoder();
    // etc.
}
</script>
@endsection

@section('styles')
<style>
    .badge {
        font-size: 0.85em;
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.03);
    }
    .btn-group .btn {
        margin-right: 5px;
    }
    .btn-group .btn:last-child {
        margin-right: 0;
    }
</style>
@endsection

@include('admin.partials.scripts')