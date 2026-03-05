@extends('layouts.app')

@section('content')
<div id="notification-container" class="fixed top-5 left-5 right-5 z-50"></div>
<div class="flex flex-col justify-center min-h-screen bg-gray-100 px-4 py-8">
    <div class="w-full max-w-4xl p-6 md:p-8 bg-white shadow-lg rounded-xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <div class="min-w-0">
                <h2 class="text-3xl font-bold text-gray-800 truncate">{{ $event['name'] }}</h2>
                <p id="list-name" class="text-lg text-gray-600 truncate">Attendance Scanner</p>
            </div>
            <a href="/instructor/event/{{ $eventId }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-150 ease-in-out whitespace-nowrap">Back to Event</a>
        </div>

        <!-- QR Code Scanner -->
        <div class="bg-gray-50 p-6 rounded-2xl shadow-lg mb-8">
            <div class="flex flex-col items-center justify-center text-center">
                <div id="scanner-ui" class="w-[90%] max-w-xl mx-auto">
                    <!-- Reader container -->
                    <div id="reader-container" class="relative w-full aspect-square mx-auto rounded-2xl overflow-hidden hidden">
                        <div id="reader" class="w-full h-full"></div>
                    </div>
                    <button id="scan-qr-btn" class="mt-6 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-full focus:outline-none focus:ring-4 focus:ring-indigo-300 text-lg transition-all duration-300 ease-in-out transform hover:scale-105 shadow-md">
                        <span id="scan-btn-text" class="flex items-center justify-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m0 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5h-4m0 0v-4m0 4l-5-5"></path></svg>
                            Start Scanner
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Attended Students List -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b">
                <h3 class="text-2xl font-bold text-gray-800">Attended Students ({{ count($attendance) }})</h3>
            </div>
            @if (count($attendance) > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach ($attendance as $studentUid => $timestamp)
                        <li class="p-4 flex justify-between items-center hover:bg-gray-50 transition cursor-pointer student-entry" data-student-uid="{{ $studentUid }}">
                            <div class="flex flex-col sm:flex-row sm:items-center">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                        <span class="font-bold text-gray-600">{{ substr($students[$studentUid]['firstName'], 0, 1) }}{{ substr($students[$studentUid]['lastName'], 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $students[$studentUid]['firstName'] }} {{ $students[$studentUid]['lastName'] }}</p>
                                        <p class="text-sm text-gray-500">ID: {{ $students[$studentUid]['schoolId'] }}</p>
                                    </div>
                                </div>
                                <span class="text-sm text-gray-600 mt-2 sm:mt-0 sm:ml-4">{{ date('F j, Y, g:i a', $timestamp) }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-center py-10 px-6">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No students yet</h3>
                    <p class="mt-1 text-sm text-gray-500">No students have marked their attendance yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@include('partials.student-details-modal')

<script src="https://unpkg.com/html5-qrcode"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
<script src="{{ asset('js/StudentDetailsModal.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notificationContainer = document.getElementById('notification-container');

        function showNotification(message, isSuccess) {
            const notification = document.createElement('div');
            notification.className = `p-4 rounded-md shadow-lg text-white ${isSuccess ? 'bg-green-500' : 'bg-red-500'}`;
            notification.textContent = message;
            notificationContainer.appendChild(notification);
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // Student Details Modal Logic
        const students = @json($students);
        const studentDetailsModal = new StudentDetailsModal('student-details-modal', students);
        studentDetailsModal.initializeTriggers('.student-entry');

        // Scanner Logic
        const scanButton = document.getElementById('scan-qr-btn');
        const scanButtonText = document.getElementById('scan-btn-text');
        const readerContainer = document.getElementById('reader-container');
        const reader = new Html5Qrcode("reader");
        let isScanning = false;

        const onScanSuccess = (decodedText, decodedResult) => {
            if (!isScanning) return;
            isScanning = false;

            reader.stop().then(() => {
                readerContainer.classList.add('hidden');

                scanButtonText.innerHTML = `<svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m0 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5h-4m0 0v-4m0 4l-5-5"></path></svg> Start Scanner`;
                scanButton.classList.remove('bg-red-500', 'hover:bg-red-600');
                scanButton.classList.add('bg-indigo-600', 'hover:bg-indigo-700');

                fetch('/api/event-attendance', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ studentUid: decodedText, eventId: "{{ $eventId }}" })
                })
                .then(response => response.json())
                .then(data => {
                    showNotification(data.message, data.success);
                    if (data.success) {
                        setTimeout(() => location.reload(), 1000);
                    }
                }).catch(error => {
                    showNotification('An error occurred. Please try again.', false);
                });
            });
        };

        const onScanFailure = (error) => { /* ignore */ };

        const resetScannerUI = () => {
            isScanning = false;
            readerContainer.classList.add('hidden');
            scanButtonText.innerHTML = `<svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m0 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5h-4m0 0v-4m0 4l-5-5"></path></svg> Start Scanner`;
            scanButton.classList.remove('bg-red-500', 'hover:bg-red-600');
            scanButton.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
        };
        
        scanButton.addEventListener('click', () => {
            if (isScanning) {
                reader.stop().then(() => {
                    resetScannerUI();
                }).catch(err => {
                    console.error("Error stopping the scanner manually:", err);
                    resetScannerUI();
                });
            } else {
                isScanning = true;
                readerContainer.classList.remove('hidden');
                scanButtonText.innerHTML = `<svg class="w-6 h-6 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 4v.01M12 20v.01M4 12h.01M20 12h.01M6.31 6.31l.01.01M17.69 17.69l.01.01M6.31 17.69l.01-.01M17.69 6.31l.01-.01" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg> Stop Scanner`;
                scanButton.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                scanButton.classList.add('bg-red-500', 'hover:bg-red-600');
                
                reader.start(
                    { facingMode: "environment" },
                    { fps: 10 },
                    onScanSuccess,
                    onScanFailure
                ).catch((err) => {
                    resetScannerUI();
                    showNotification('Unable to start scanner. Please grant camera permissions.', false);
                });
            }
        });
    });
</script>
@endsection
