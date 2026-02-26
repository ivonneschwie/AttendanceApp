@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-2xl bg-white shadow-md rounded-lg p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">{{ $room['name'] }}</h2>
            <a href="/dashboard" class="text-blue-500 hover:text-blue-700">Back to Dashboard</a>
        </div>
        
        <div class="mb-4">
            <p><span class="font-semibold">Section:</span> {{ $room['section'] }}</p>
            <p><span class="font-semibold">Subject:</span> {{ $room['subject'] }}</p>
        </div>
    </div>
</div>
@endsection
