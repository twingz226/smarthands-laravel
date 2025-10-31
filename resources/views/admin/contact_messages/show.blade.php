@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Message Details</h1>
    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-2">Name</dt>
                <dd class="col-sm-10">{{ $message->name }}</dd>

                <dt class="col-sm-2">Email</dt>
                <dd class="col-sm-10">{{ $message->email }}</dd>

                <dt class="col-sm-2">Phone</dt>
                <dd class="col-sm-10">{{ $message->phone }}</dd>

                <dt class="col-sm-2">Message</dt>
                <dd class="col-sm-10">{{ $message->message }}</dd>

                <dt class="col-sm-2">Date</dt>
                <dd class="col-sm-10">{{ $message->created_at->format('Y-m-d H:i') }}</dd>
            </dl>
            <a href="{{ route('admin.contact_messages.index') }}" class="btn btn-secondary">
                <i class="entypo-arrow-left"></i> Back to List
            </a>
            <form id="delete-form-{{ $message->id }}" action="{{ route('admin.contact_messages.destroy', $message->id) }}" method="POST" style="display:none;">
                @csrf
                @method('DELETE')
            </form>
            <button type="button" class="btn btn-danger" data-delete-form="delete-form-{{ $message->id }}">
                <i class="entypo-trash"></i> Delete Message
            </button>
        </div>
    </div>
</div>
@endsection
