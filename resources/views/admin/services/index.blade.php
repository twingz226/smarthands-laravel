@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Service Catalog</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('services.create') }}" class="btn btn-primary">
                <i class="entypo-plus"></i> Add New Service
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
                                    ₱{{ number_format($service->price, 2) }} / hr (min {{ $service->duration_minutes / 60 }} hrs)
                                @endif
                            </td>
                            <td>
                                @if ($service->pricing_type === 'duration')
                                    min {{ $service->duration_minutes / 60 }} hrs
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('services.show', $service->id) }}"
                                       class="btn btn-info rounded-circle"
                                       data-tooltip="View">
                                        <i class="entypo-eye"></i>
                                    </a>
                                    <a href="{{ route('services.edit', $service->id) }}"
                                       class="btn btn-warning rounded-circle"
                                       data-tooltip="Edit">
                                        <i class="entypo-pencil"></i>
                                    </a>
                                    <form action="{{ route('services.destroy', $service->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-danger rounded-circle"
                                                data-tooltip="Delete"
                                                data-action="delete-service"
                                                data-service-name="{{ $service->name }}">
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
    $(document).on('click', 'button[data-action="delete-service"]', function(e){
        e.preventDefault();
        formToSubmit = $(this).closest('form');
        var name = $(this).data('service-name');
        var msg = 'Are you sure you want to delete this service? This action cannot be undone.';
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="confirmDeleteLabel">Confirm Deletion</h4>
            </div>
            <div class="modal-body">
                <p id="confirmDeleteMessage">Are you sure you want to delete this service? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>
@endpush
