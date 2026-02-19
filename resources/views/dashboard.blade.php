@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-sm bg-white shadow-md rounded-lg p-8 text-center">
        <h2 class="text-2xl font-bold mb-4">Welcome, {{ $userName }}</h2>
        <div class="mb-6">
            <x-qrcode :qrCode="$qrCode" />
        </div>
        <a href="/logout" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Logout</a>
    </div>
</div>
@endsection
