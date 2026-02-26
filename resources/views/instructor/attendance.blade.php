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
                <h3 class="text-xl font-semibold mb-4">Scan Student QR Code</h3>
                <button id="scan-qr-btn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Scan QR Code</button>
                <div id="reader" style="width: 500px;"></div>
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

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scanButton = document.getElementById('scan-qr-btn');
        const reader = new Html5Qrcode("reader");

        scanButton.addEventListener('click', () => {
            reader.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: 250
                },
                (decodedText, decodedResult) => {
                    const roomCode = "{{ $roomCode }}";
                    fetch('/api/attendance', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            studentUid: decodedText,
                            roomCode: roomCode
                        })
                    }).then(response => response.json()).then(data => {
                        if (data.success) {
                            alert('Attendance marked successfully!');
                            location.reload();
                        } else {
                            alert('Failed to mark attendance.');
                        }
                    });
                    reader.stop();
                },
                (errorMessage) => {
                    // parse error, ignore it.
                })
                .catch((err) => {
                    // Start failed, handle it.
                });
        });
    });
</script>
@endsection
