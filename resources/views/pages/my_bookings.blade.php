@extends('layouts.app')

@section('content')
    @include('pages.my_bookings_content', ['user' => $user])
@endsection
