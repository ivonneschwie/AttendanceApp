@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">{{ $event['name'] }}</h1>
        <a href="{{ route('events.scan', ['event' => $eventId]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Scan QR Code</a>
    </div>
    <p class="text-gray-600 mb-4">Date: {{ $event['date'] }}</p>

    <h2 class="text-xl font-bold mb-2">Attendance</h2>
    <div class="bg-white shadow-md rounded my-6">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Student Name</th>
                    <th class="py-3 px-6 text-left">Time</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @if ($attendance)
                    @foreach ($attendance as $studentId => $details)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left whitespace-nowrap">
                                {{ $details['name'] }}
                            </td>
                            <td class="py-3 px-6 text-left">
                                {{ date('Y-m-d H:i:s', $details['timestamp'] / 1000) }}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2" class="text-center py-4">No attendance records found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
