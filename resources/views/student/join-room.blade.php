@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="w-full max-w-lg p-8 bg-white shadow-md rounded-lg">
        <h2 class="text-3xl font-bold mb-6 text-center">Join a Room</h2>

        <form action="/student/join-room" method="POST">
            @csrf

            <div class="mb-4">
                <label for="room_code" class="block text-gray-700 text-sm font-bold mb-2">Room Code</label>
                <input type="text" id="room_code" name="room_code" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Join Room
                </button>
                <a href="/dashboard" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
