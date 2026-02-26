@extends('layouts.app')

@section('content')
<div class="flex flex-col justify-center min-h-screen bg-gray-100 px-4 py-8">
    <div class="w-full max-w-4xl p-4 md:p-8 bg-white shadow-md rounded-lg mx-auto">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
            <h2 class="text-3xl font-bold mb-4 sm:mb-0 text-center sm:text-left">Room Details</h2>
            <div class="flex flex-col sm:flex-row">
                <a href="/instructor/room/{{ $roomCode }}/attendance" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full sm:w-auto mb-2 sm:mb-0 sm:mr-2 text-center">Attendance</a>
                <a href="/instructor/dashboard" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full sm:w-auto text-center">Back to Dashboard</a>
            </div>
        </div>

        <div class="mb-4">
            <p class="text-lg font-semibold">Name: <span class="font-normal">{{ $room['name'] }}</span></p>
            <p class="text-lg font-semibold">Section: <span class="font-normal">{{ $room['section'] }}</span></p>
            <p class="text-lg font-semibold">Subject: <span class="font-normal">{{ $room['subject'] }}</span></p>
            <p class="text-lg font-semibold">Room Code: <span class="font-normal">{{ $roomCode }}</span></p>
        </div>

        <h3 class="text-2xl font-bold mb-4">Students in this Room</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="w-full bg-gray-200 text-left text-sm font-semibold text-gray-700">
                        <th class="py-3 px-4">First Name</th>
                        <th class="py-3 px-4">Last Name</th>
                        <th class="py-3 px-4">School ID</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($students as $student)
                        <tr>
                            <td class="py-3 px-4">{{ $student['firstName'] }}</td>
                            <td class="py-3 px-4">{{ $student['lastName'] }}</td>
                            <td class="py-3 px-4">{{ $student['schoolId'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-3 px-4 text-center">No students have joined this room yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
