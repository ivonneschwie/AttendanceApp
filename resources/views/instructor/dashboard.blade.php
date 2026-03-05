@extends('layouts.app')

@section('content')
<div class="flex flex-col justify-center min-h-screen bg-gray-100 px-4 py-8">
    <div class="w-full max-w-4xl p-4 md:p-8 bg-white shadow-md rounded-lg mx-auto">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
            <h2 class="text-3xl font-bold mb-4 md:mb-0 text-center md:text-left">Welcome, Instructor!</h2>
            <div class="flex flex-col md:flex-row">
                <a href="/instructor/create-room" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full md:w-auto mb-2 md:mb-0 md:mr-2 text-center">Create Room</a>
                <a href="/instructor/events" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full md:w-auto mb-2 md:mb-0 md:mr-2 text-center">Events</a>
                <a href="/logout" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full md:w-auto text-center">Logout</a>
            </div>
        </div>

        <h3 class="text-2xl font-bold mb-4">Your Rooms</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="w-full bg-gray-200 text-left text-sm font-semibold text-gray-700">
                        <th class="py-3 px-4">Subject</th>
                        <th class="py-3 px-4">Subject Code</th>
                        <th class="py-3 px-4">Room Code</th>
                        <th class="py-3 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($rooms as $roomCode => $room)
                        <tr class="border-b border-gray-200">
                            <td class="py-3 px-4 whitespace-nowrap">{{ $room['name'] }}</td>
                            <td class="py-3 px-4 whitespace-nowrap">{{ $room['subject'] }}</td>
                            <td class="py-3 px-4 whitespace-nowrap">{{ $roomCode }}</td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <a href="/instructor/room/{{ $roomCode }}" class="text-blue-500 hover:text-blue-700">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-3 px-4 text-center">You haven't created any rooms yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
