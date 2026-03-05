@extends('layouts.app')

@section('content')
<div class="flex flex-col justify-center min-h-screen bg-gray-100 px-4 py-8">
    <div class="w-full max-w-lg p-4 md:p-8 bg-white shadow-md rounded-lg mx-auto">
        <h2 class="text-3xl font-bold mb-6 text-center">Create Event</h2>
        <form action="/instructor/create-event" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Event Name</label>
                <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Create</button>
            </div>
        </form>
    </div>
</div>
@endsection
