@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="w-full max-w-lg p-8 bg-white shadow-md rounded-lg">
        <h2 class="text-3xl font-bold mb-6 text-center">Create a New Room</h2>

        <form action="/instructor/create-room" method="POST">
            @csrf

            <div class="mb-4">
                <label for="subject" class="block text-gray-700 text-sm font-bold mb-2">Subject</label>
                <input type="text" id="subject" name="subject" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label for="section" class="block text-gray-700 text-sm font-bold mb-2">Section</label>
                <input type="text" id="section" name="section" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label for="subject_code" class="block text-gray-700 text-sm font-bold mb-2">Subject Code</label>
                <input type="text" id="subject_code" name="subject_code" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Create Room
                </button>
                <a href="/instructor/dashboard" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
