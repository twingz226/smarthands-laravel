@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="main-content">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>📋 Customer Database</h3>
                <div>
                    <a href="{{ request()->url() }}?archived={{ !$showArchived }}"
                       class="toggle-archive-btn {{ $showArchived ? 'primary' : 'outline' }}">
                        <i class="fas {{ $showArchived ? 'fa-eye' : 'fa-archive' }}"></i>
                        {{ $showArchived ? 'Show Active Customers' : 'Show Archived Customers' }}
                    </a>
                </div>
            </div>
            <table class="table table-hover customer-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Registered Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->contact }}</td>

                            <td>{{ $customer->registered_date ? $customer->registered_date->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                @if($customer->is_archived)
                                    <span class="badge bg-secondary">Archived</span>
                                    <small class="d-block text-muted">{{ $customer->archived_at->format('M d, Y') }}</small>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.customers.show', $customer->id) }}"
                                       class="btn btn-info rounded-circle"
                                       data-tooltip="View">
                                        <i class="entypo-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.customers.edit', $customer->id) }}"
                                       class="btn btn-warning rounded-circle"
                                       data-tooltip="Edit">
                                        <i class="entypo-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $customers->appends(['archived' => $showArchived])->links() }}
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Button Styles */
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
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.btn.rounded-circle:hover {
    transform: translateY(-2px) scale(1.1);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.btn.rounded-circle i {
    margin: 0;
    font-size: 14px;
}

/* Tooltip Styles */
[data-tooltip] {
    position: relative;
}

[data-tooltip]:before {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    padding: 6px 12px;
    background: #2c3e50;
    color: white;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
    white-space: nowrap;
    visibility: hidden;
    opacity: 0;
    transition: all 0.2s ease;
    z-index: 1000;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

[data-tooltip]:hover:before {
    visibility: visible;
    opacity: 1;
    bottom: calc(100% + 5px);
}

/* Table Styles */
.customer-table {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    border-collapse: separate;
    border-spacing: 0;
}

.customer-table thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #e9ecef;
    padding: 12px 15px;
    font-weight: 600;
    color: #495057;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}

.customer-table tbody tr {
    transition: all 0.2s ease;
}

.customer-table tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.customer-table tbody td {
    padding: 12px 15px;
    vertical-align: middle;
    border-top: 1px solid #e9ecef;
    color: #495057;
}

/* Status Badges */
.badge {
    padding: 6px 10px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge.bg-success {
    background-color: #28a745 !important;
}

.badge.bg-secondary {
    background-color: #6c757d !important;
}

/* Responsive Table */
@media (max-width: 768px) {
    .customer-table {
        display: block;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .main-content .container {
        padding: 0 10px;
    }
}

/* Pagination Styles */
.pagination {
    margin: 20px 0;
    display: flex;
    justify-content: center;
}

.pagination .page-link {
    color: #2c3e50;
    border: 1px solid #dee2e6;
    margin: 0 3px;
    border-radius: 4px !important;
    transition: all 0.2s;
}

.pagination .page-item.active .page-link {
    background-color: #2c3e50;
    border-color: #2c3e50;
}

.pagination .page-link:hover {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}
</style>
@endpush
