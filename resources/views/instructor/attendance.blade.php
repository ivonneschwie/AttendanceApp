@extends('layouts.app')

@section('content')
<div id="notification-container" class="fixed top-5 left-5 right-5 z-50"></div>
<div class="flex flex-col justify-center min-h-screen bg-gray-100 px-4 py-8">
    <div class="w-full max-w-4xl p-6 md:p-8 bg-white shadow-lg rounded-xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <div class="min-w-0">
                <h2 class="text-3xl font-bold text-gray-800 truncate">{{ $room['name'] }}</h2>
                <p id="list-name" class="text-lg text-gray-600 truncate">{{ $listName }}</p>
            </div>
            <div class="flex-shrink-0 flex items-center space-x-2 ml-4">
                <button id="edit-button" class="text-gray-500 hover:text-gray-700 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </button>
                <button id="delete-button" class="text-gray-500 hover:text-red-700 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
                <a href="/instructor/room/{{ $roomCode }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-150 ease-in-out whitespace-nowrap">Back to Room</a>
            </div>
        </div>

        <!-- QR Code Scanner -->
        <div class="bg-gray-50 p-6 rounded-xl shadow mb-8">
            <h3 class="text-xl font-bold mb-4 text-gray-700">Scan QR Code</h3>
            <div class="flex flex-col items-center justify-center text-center">
                <button id="scan-qr-btn" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-full focus:outline-none focus:shadow-outline text-lg transition duration-150 ease-in-out">Start Scanner</button>
                <div id="reader" class="w-full max-w-xs mt-4 rounded-lg overflow-hidden"></div>
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
                        <li class="p-4 flex justify-between items-center hover:bg-gray-50 transition">
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
                                <span class="text-sm text-gray-600 mt-2 sm:mt-0 sm:ml-4">{{ date('F j, Y, g:i a', $timestamp / 1000) }}</span>
                            </div>
                            <form action="/instructor/room/{{ $roomCode }}/attendance/{{ $listId }}/entry/{{ $studentUid }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 p-2 rounded-full transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
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

<!-- Edit Modal -->
<div id="edit-modal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center md:block md:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden md:inline-block md:align-middle md:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all md:my-8 md:align-middle md:max-w-lg md:w-full">
            <form id="edit-form-modal" action="/instructor/room/{{ $roomCode }}/attendance/{{ $listId }}/update" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 md:p-6 md:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Attendance List Name</h3>
                    <div class="mt-2">
                        <input type="text" name="name" id="name-input" value="{{ $listName }}" class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 md:px-6 md:flex md:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 md:ml-3 md:w-auto md:text-sm">Update</button>
                    <button type="button" id="cancel-button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 md:mt-0 md:w-auto md:text-sm">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center md:block md:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true"><div class="absolute inset-0 bg-gray-500 opacity-75"></div></div>
        <span class="hidden md:inline-block md:align-middle md:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all md:my-8 md:align-middle md:max-w-lg md:w-full">
            <form id="delete-form" action="/instructor/room/{{ $roomCode }}/attendance/{{ $listId }}/delete" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 md:p-6 md:pb-4">
                    <div class="md:flex md:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 md:mx-0 md:h-10 md:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                        <div class="mt-3 text-center md:mt-0 md:ml-4 md:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Attendance List</h3>
                            <p class="text-sm text-gray-500">Are you sure you want to delete this list? This action cannot be undone.</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 md:px-6 md:flex md:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 md:ml-3 md:w-auto md:text-sm">Delete</button>
                    <button type="button" id="cancel-delete-button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 md:mt-0 md:w-auto md:text-sm">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
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

        // Edit Modal Logic
        const editButton = document.getElementById('edit-button');
        const editModal = document.getElementById('edit-modal');
        const cancelButton = document.getElementById('cancel-button');
        const editForm = document.getElementById('edit-form-modal');
        const listName = document.getElementById('list-name');

        editButton.addEventListener('click', () => editModal.classList.remove('hidden'));
        cancelButton.addEventListener('click', () => editModal.classList.add('hidden'));

        editForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(editForm);
            const response = await fetch(editForm.action, { method: 'POST', body: formData });
            if (response.ok) {
                listName.textContent = formData.get('name');
                editModal.classList.add('hidden');
                showNotification('Attendance list name updated successfully.', true);
            } else {
                showNotification('Failed to update attendance list name.', false);
            }
        });

        // Delete Modal Logic
        const deleteButton = document.getElementById('delete-button');
        const deleteModal = document.getElementById('delete-modal');
        const cancelDeleteButton = document.getElementById('cancel-delete-button');

        deleteButton.addEventListener('click', (e) => {
            e.preventDefault();
            deleteModal.classList.remove('hidden');
        });
        cancelDeleteButton.addEventListener('click', () => deleteModal.classList.add('hidden'));

        // Scanner Logic
        const scanButton = document.getElementById('scan-qr-btn');
        const reader = new Html5Qrcode("reader");

        scanButton.addEventListener('click', () => {
            scanButton.textContent = 'Scanning...';
            reader.start(
                { facingMode: "environment" }, 
                { fps: 10, qrbox: 250 },
                (decodedText, decodedResult) => {
                    reader.stop();
                    scanButton.textContent = 'Start Scanner';
                    fetch('/api/attendance', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ studentUid: decodedText, roomCode: "{{ $roomCode }}", listId: "{{ $listId }}" })
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
                },
                (errorMessage) => { /* ignore */ }
            ).catch((err) => {
                scanButton.textContent = 'Start Scanner';
                showNotification('Unable to start scanner.', false);
            });
        });
    });
</script>
@endsection