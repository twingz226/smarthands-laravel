@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Service Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('services.index') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 200px">Service Name</th>
                                    <td>{{ $service->name }}</td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td>{{ $service->description }}</td>
                                </tr>
                                <tr>
                                    <th>Pricing Type</th>
                                    <td>{{ ucfirst($service->pricing_type) }}</td>
                                </tr>
                                <tr>
                                    <th>Price</th>
                                    <td>
                                        @if ($service->pricing_type === 'sqm')
                                            ₱{{ number_format($service->price, 2) }} / sqm
                                        @else
                                            ₱{{ number_format($service->price, 2) }} / hr (min {{ $service->duration_minutes / 60 }} hrs)
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Duration</th>
                                    <td>
                                        @if ($service->pricing_type === 'duration')
                                            min {{ $service->duration_minutes / 60 }} hrs
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($service->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $service->created_at->format('M d, Y H:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Last Updated</th>
                                    <td>{{ $service->updated_at->format('M d, Y H:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection