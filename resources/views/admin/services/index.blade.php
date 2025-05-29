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
        <h1 class="h2">Service Catalog</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('services.create') }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-plus-circle"></i> Add New Service
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Duration / Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($services as $service)
                        <tr>
                            <td>{{ $service->id }}</td>
                            <td>{{ $service->name }}</td>
                            <td>{{ Str::limit($service->description, 50) }}</td>
                            <td>
                                @if ($service->pricing_type === 'sqm')
                                    ₱{{ number_format($service->price, 2) }} / sqm
                                @else
                                    ₱{{ number_format($service->price, 2) }} for {{ $service->duration_minutes }} mins
                                @endif
                            </td>
                            <td>
                                @if ($service->pricing_type === 'duration')
                                    {{ $service->duration_minutes }} mins
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('services.show', $service->id) }}" class="btn btn-sm btn-info" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('services.edit', $service->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('services.destroy', $service->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
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

@include('admin.partials.scripts')
