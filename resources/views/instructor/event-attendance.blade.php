@extends('layouts.app')

@section('content')
<div class="flex flex-col justify-center min-h-screen bg-gray-100 px-4 py-8">
    <div class="w-full max-w-4xl p-4 md:p-8 bg-white shadow-md rounded-lg mx-auto">
        <h2 class="text-3xl font-bold mb-6 text-center">{{ $event['name'] }} - Attendance</h2>

        <div class="flex justify-center mb-6">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title mb-4">Scan QR Code to Mark Attendance</h5>
                    @component('components.qrcode', ['id' => 'event-attendance-qrcode', 'qrData' => json_encode(['eventId' => $eventId])])
                    @endcomponent
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="w-full bg-gray-200 text-left text-sm font-semibold text-gray-700">
                        <th class="py-3 px-4">Name</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Time</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($students as $student)
                        <tr class="border-b border-gray-200">
                            <td class="py-3 px-4 whitespace-nowrap">{{ $student['name'] }}</td>
                            <td class="py-3 px-4 whitespace-nowrap">{{ $student['email'] }}</td>
                            <td class="py-3 px-4 whitespace-nowrap">{{ date('Y-m-d H:i:s', $student['timestamp']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-3 px-4 text-center">No students have marked attendance yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
