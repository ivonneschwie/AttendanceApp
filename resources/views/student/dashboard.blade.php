@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">Welcome, {{ $userName }}!</h1>
            <div class="flex space-x-2">
                <a href="/student/join-room" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline w-full md:w-auto text-center">Join Room</a>
                <a href="/logout" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline w-full md:w-auto text-center">Logout</a>
            </div>
        </div>

        <div class="md:grid md:grid-cols-2 lg:grid-cols-3 md:gap-8">

            <!-- Left column for QR Code -->
            <div class="md:col-span-1 mb-8 md:mb-0">
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <h2 class="text-2xl font-bold mb-4">Your QR Code</h2>
                    <div class="flex justify-center w-full">
                        <div class="w-4/5 mx-auto md:w-full md:max-w-xs [&>svg]:w-full [&>svg]:h-auto">
                           {!! $qrCode !!}
                        </div>
                    </div>
                    <p class="mt-4 text-gray-600">Scan this to mark your attendance.</p>
                </div>
            </div>

            <!-- Right column for Rooms -->
            <div class="md:col-span-1 lg:col-span-2">
                <h2 class="text-2xl font-bold mb-4 text-gray-800">Your Rooms</h2>
                <div class="bg-white rounded-lg shadow-lg">
                    <ul class="divide-y divide-gray-200">
                        @if(isset($rooms) && is_array($rooms) && count($rooms) > 0)
                            @foreach($rooms as $roomCode => $room)
                                <li class="p-4 hover:bg-gray-50 transition duration-150 ease-in-out">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="font-bold text-lg text-gray-900">{{ $room['name'] }}</div>
                                            <div class="text-sm text-gray-600">{{ $room['subject'] }} - {{ $room['section'] }}</div>
                                        </div>
                                        <a href="/student/room/{{ $roomCode }}" class="text-blue-500 hover:text-blue-700 font-semibold">View</a>
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <li class="p-4 text-center text-gray-500">
                                You haven't joined any rooms yet.
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
