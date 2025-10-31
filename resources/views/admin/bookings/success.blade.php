<!-- resources/views/bookings/success.blade.php -->
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100">
  <div class="container p-5 bg-white rounded shadow-lg text-center">
    <h2 class="text-success">🎉 Booking Successful! 🎉</h2>
    <p>Thank you for choosing SmartHands Cleaning Services!</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Return Home</a>
  </div>
</div>
@endsection