@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Contact Messages</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($messages as $msg)
                    <tr>
                        <td>{{ $msg->name }}</td>
                        <td>{{ $msg->email }}</td>
                        <td>{{ $msg->phone }}</td>
                        <td>{{ Str::limit($msg->message, 40) }}</td>
                        <td>{{ $msg->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.contact_messages.show', $msg->id) }}" class="btn btn-sm btn-info">View</a>
                            <form id="delete-form-{{ $msg->id }}" action="{{ route('admin.contact_messages.destroy', $msg->id) }}" method="POST" style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button type="button" class="btn btn-sm btn-danger" data-delete-form="delete-form-{{ $msg->id }}">
                                <i class="entypo-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">No messages found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        {{ $messages->links() }}
    </div>
</div>
@endsection
