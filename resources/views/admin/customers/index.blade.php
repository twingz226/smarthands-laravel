@include('admin.partials.header')

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

<div class="main-content">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>📋 Customer Database</h3>
            <div>
                <a href="{{ request()->url() }}?archived={{ !$showArchived }}" 
                   class="btn {{ $showArchived ? 'btn-success' : 'btn-outline-primary' }} fw-bold"
                   style="{{ !$showArchived ? 'color:rgb(253, 248, 248) !important; border-color: rgb(5, 51, 100) !important; background-color: #303641 !important;' : '' }}"
                   onmouseover="if(this.classList.contains('btn-outline-primary')){this.style.setProperty('background-color', '#28a745', 'important'); this.style.setProperty('color', 'white', 'important'); this.style.setProperty('border-color', '#28a745', 'important');}"
                   onmouseout="if(this.classList.contains('btn-outline-primary')){this.style.setProperty('background-color', '#303641', 'important'); this.style.setProperty('color', 'rgb(246, 239, 239)', 'important'); this.style.setProperty('border-color', 'rgb(5, 51, 100)', 'important');}">
                    {{ $showArchived ? 'Show Active Customers' : 'Show Archived Customers' }}
                </a>
            </div>
        </div>
        <table class="table table-bordered customer-table">
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Registered Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td>{{ $customer->id }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->contact }}</td>
                        <td>{{ $customer->address }}</td>
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
                                <a href="{{ route('customers.show', $customer->id) }}" 
                                   class="btn btn-info rounded-circle" 
                                   data-tooltip="View">
                                    <i class="entypo-eye"></i>
                                </a>
                                <a href="{{ route('customers.edit', $customer->id) }}" 
                                   class="btn btn-warning rounded-circle" 
                                   data-tooltip="Edit">
                                    <i class="entypo-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No customers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        {{ $customers->appends(['archived' => $showArchived])->links() }}
    </div>
</div>

@include('admin.partials.scripts')
