@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-md rounded-lg p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">{{ $room['name'] }} - Attendance</h2>
            <a href="/instructor/dashboard" class="text-blue-500 hover:text-blue-700">Back to Dashboard</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="text-center">
                <h3 class="text-xl font-semibold mb-4">Scan to Mark Attendance</h3>
                <div class="flex justify-center">
                    <x-qrcode :qrCode="$qrCode" />
                </div>
            </div>

            <div>
                <h3 class="text-xl font-semibold mb-4">Attended Students</h3>
                @if (count($attendance) > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach ($attendance as $studentUid => $timestamp)
                            <li class="py-4 flex justify-between items-center">
                                <span>{{ $students[$studentUid]['firstName'] }} {{ $students[$studentUid]['lastName'] }}</span>
                                <span class="text-gray-500">{{ date('Y-m-d H:i:s', $timestamp / 1000) }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>No students have marked their attendance yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
