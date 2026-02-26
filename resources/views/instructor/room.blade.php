@extends('layouts.app')

@section('content')
<div class="flex flex-col justify-center min-h-screen bg-gray-100 px-4 py-8">
    <div class="w-full max-w-4xl p-4 md:p-8 bg-white shadow-md rounded-lg mx-auto">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
            <h2 class="text-3xl font-bold mb-4 md:mb-0 text-center md:text-left">Room Details</h2>
            <a href="/instructor/dashboard" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full md:w-auto text-center">Back to Dashboard</a>
        </div>

        <div class="mb-8 bg-gray-50 p-6 rounded-lg">
            <h3 class="text-2xl font-bold mb-4 border-b pb-2">Room Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <p class="text-lg"><span class="font-semibold">Name:</span> {{ $room['name'] }}</p>
                <p class="text-lg"><span class="font-semibold">Section:</span> {{ $room['section'] }}</p>
                <p class="text-lg"><span class="font-semibold">Subject:</span> {{ $room['subject'] }}</p>
                <p class="text-lg"><span class="font-semibold">Room Code:</span> <span class="font-mono bg-gray-200 px-2 py-1 rounded">{{ $roomCode }}</span></p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <div class="flex flex-nowrap justify-between items-center mb-4">
                    <h3 class="text-2xl font-bold truncate">Attendance Lists</h3>
                    <div class="flex-shrink-0 ml-4">
                        <form action="/instructor/room/{{ $roomCode }}/attendance" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 mr-2 rounded focus:outline-none focus:shadow-outline whitespace-nowrap">Create</button>
                        </form>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md">
                    <ul class="divide-y divide-gray-200">
                        @forelse($attendanceLists as $listId => $list)
                            @if(is_array($list))
                                <li class="p-4 flex flex-nowrap justify-between items-center hover:bg-gray-50 transition">
                                    <span class="text-gray-800 font-medium truncate">{{ $list['name'] }}</span>
                                    <a href="/instructor/room/{{ $roomCode }}/attendance/{{ $listId }}" class="flex-shrink-0 ml-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md text-sm">View</a>
                                </li>
                            @endif
                        @empty
                            <li class="p-4 text-center text-gray-500">No attendance lists created yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div>
                <h3 class="text-2xl font-bold mb-4">Students in this Room</h3>
                <div class="bg-white rounded-lg shadow-md">
                    <ul class="divide-y divide-gray-200">
                        @forelse($students as $student)
                             <li class="p-4 flex items-center justify-between hover:bg-gray-50 transition">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $student['firstName'] }} {{ $student['lastName'] }}</p>
                                    <p class="text-sm text-gray-500">ID: {{ $student['schoolId'] }}</p>
                                </div>
                            </li>
                        @empty
                            <li class="p-4 text-center text-gray-500">No students have joined this room yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
