@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h3 class="mb-3"><i class="entypo-calendar"></i> Disabled Dates</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Add Disabled Date</h4>
        </div>
        <div class="panel-body">
            <form method="POST" action="{{ route('admin.disabled_dates.store') }}" class="form-inline">
                @csrf
                <div class="form-group mb-2 me-2">
                    <label for="date" class="me-2">Date</label>
                    <input type="date" id="date" name="date" class="form-control" required>
                </div>
                <div class="form-group mb-2 me-2" style="min-width: 320px;">
                    <label for="reason" class="me-2">Reason</label>
                    <input type="text" id="reason" name="reason" class="form-control" placeholder="Holiday, maintenance, etc.">
                </div>
                <button type="submit" class="btn btn-primary mb-2">Add</button>
            </form>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Disabled Dates List</h4>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dates as $d)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($d->date)->format('M d, Y') }}</td>
                                <td>{{ $d->reason ?? '-' }}</td>
                                <td>
                                    <span class="label {{ $d->is_active ? 'label-danger' : 'label-default' }}">
                                        {{ $d->is_active ? 'Disabled' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="d-flex" style="gap:8px;">
                                    <form method="POST" action="{{ route('admin.disabled_dates.update', $d) }}" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="is_active" value="{{ $d->is_active ? 0 : 1 }}">
                                        <input type="hidden" name="reason" value="{{ $d->reason }}">
                                        <button type="submit" class="btn btn-xs {{ $d->is_active ? 'btn-default' : 'btn-success' }}">
                                            {{ $d->is_active ? 'Mark Inactive' : 'Activate' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.disabled_dates.destroy', $d) }}" class="d-inline" onsubmit="return confirm('Remove this disabled date?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No disabled dates configured.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
