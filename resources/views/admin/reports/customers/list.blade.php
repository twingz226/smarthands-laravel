@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1 class="h2 pt-3 pb-2 mb-3 border-bottom">Customer List Report</h1>
        </div>
        <div class="col-md-12 text-right">
            <div class="dropdown">
                <button class="btn btn-lg btn-secondary dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-print"></i> Export
                </button>
                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                    <li><a href="#" onclick="window.print(); return false;">
                        <i class="fas fa-print text-secondary"></i> Print
                    </a></li>
                    <li><a href="{{ route('reports.customers.export.pdf') }}">
                        <i class="fas fa-file-pdf text-danger"></i> PDF
                    </a></li>
                    <li><a href="{{ route('reports.customers.export.excel') }}">
                        <i class="fas fa-file-excel text-success"></i> Excel
                    </a></li>
                    <li><a href="{{ route('reports.customers.export.csv') }}">
                        <i class="fas fa-file-csv text-primary"></i> CSV
                    </a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Total Jobs</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->contact }}</td>
                            <td>{{ $customer->jobs_count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn-toolbar {
        display: none !important;
    }
    
    .container-fluid {
        margin: 0;
        padding: 0;
        max-width: 100%;
    }
    
    .card {
        border: none;
        box-shadow: none;
    }
    
    .table-responsive {
        overflow-x: visible;
    }
    
    body {
        font-size: 12px;
    }
    
    .table th {
        background-color: #f5f5f5 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>
@endsection