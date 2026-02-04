@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center h-screen">
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 text-center">
        <h2 class="text-2xl font-bold mb-4">Welcome, {{ session('user')['email'] }}</h2>
        <a href="/logout" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Logout</a>
    </div>
</div>
@endsection
