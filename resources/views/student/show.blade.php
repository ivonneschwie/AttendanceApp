@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 px-4 py-8">
    <div class="w-full max-w-2xl bg-white shadow-md rounded-lg p-8">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
            <h2 class="text-3xl font-bold mb-4 sm:mb-0 text-center sm:text-left">Room Details</h2>
            <a href="/dashboard" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full sm:w-auto text-center">Back to Dashboard</a>
        </div>
        
        <div class="mb-4">
            <p class="text-lg font-semibold">Name: <span class="font-normal">{{ $room['name'] }}</span></p>
            <p class="text-lg font-semibold">Section: <span class="font-normal">{{ $room['section'] }}</span></p>
            <p class="text-lg font-semibold">Subject: <span class="font-normal">{{ $room['subject'] }}</span></p>
        </div>
    </div>
</div>
@endsection
