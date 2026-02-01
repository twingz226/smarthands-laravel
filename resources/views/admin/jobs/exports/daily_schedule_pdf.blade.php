<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daily Schedule - {{ $date }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .status-assigned {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        .status-in_progress {
            background-color: #e8f5e8;
            color: #388e3c;
        }
        .no-jobs {
            text-align: center;
            font-style: italic;
            color: #666;
            margin: 50px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Daily Schedule Report</h1>
        <p>Date: {{ $date }}</p>
        <p>Assigned & In Progress Jobs</p>
    </div>

    @if($assignedJobs->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Address</th>
                    <th>Service</th>
                    <th>Assigned Employees</th>
                    <th>Status</th>
                    <th>Scheduled Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assignedJobs as $job)
                    <tr>
                        <td>{{ $job->customer ? $job->customer->name : 'N/A' }}</td>
                        <td>{{ $job->address }}</td>
                        <td>{{ $job->service ? $job->service->name : 'N/A' }}</td>
                        <td>
                            @if($job->employees->count() > 0)
                                {{ $job->employees->pluck('name')->implode(', ') }}
                            @else
                                Unassigned
                            @endif
                        </td>
                        <td class="status-{{ $job->status }}">
                            {{ ucfirst($job->status) }}
                        </td>
                        <td>{{ $job->scheduled_date->format('g:i A') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p><strong>Total Jobs: {{ $assignedJobs->count() }}</strong></p>
            <p>Generated on: {{ \Carbon\Carbon::now()->format('F j, Y g:i A') }}</p>
        </div>
    @else
        <div class="no-jobs">
            <p>No assigned or in-progress jobs for today.</p>
        </div>
    @endif
</body>
</html>
