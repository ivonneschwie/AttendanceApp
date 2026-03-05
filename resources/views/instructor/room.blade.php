@extends('layouts.app')

@section('content')
<div class="flex flex-col justify-center min-h-screen bg-gray-100 px-4 py-8">
    <div class="w-full max-w-4xl p-4 md:p-8 bg-white shadow-md rounded-lg mx-auto">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
            <a href="/instructor/dashboard" class="inline-flex items-center text-gray-500 hover:text-gray-700 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                <span>Back to Dashboard</span>
            </a>
        </div>

        <div class="mb-8 bg-gray-50 p-6 rounded-lg">
            <h3 class="text-2xl font-bold mb-4 border-b pb-2">Room Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <p class="text-lg"><span class="font-semibold">Subject:</span> {{ $room['name'] }}</p>
                <p class="text-lg"><span class="font-semibold">Section:</span> {{ $room['section'] }}</p>
                <p class="text-lg"><span class="font-semibold">Subject Code:</span> {{ $room['subject'] }}</p>
                <p class="text-lg"><span class="font-semibold">Room Code:</span> <span class="font-mono bg-gray-200 px-2 py-1 rounded">{{ $roomCode }}</span></p>
            </div>
        </div>

        <!-- Tabs -->
        <div>
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex" aria-label="Tabs">
                    <button id="tab-attendance" class="w-1/2 text-center py-4 px-1 border-b-2 font-medium text-sm border-indigo-500 text-indigo-600">
                        Attendance Lists
                    </button>
                    <button id="tab-students" class="w-1/2 text-center py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Students
                    </button>
                </nav>
            </div>
        </div>

        <!-- Tab Panels -->
        <div class="mt-6">
            <!-- Attendance List Panel -->
            <div id="panel-attendance">
                <div class="flex flex-nowrap justify-between items-center mb-4">
                    <h3 class="text-2xl font-bold truncate">Attendance Lists</h3>
                    <div class="flex-shrink-0 ml-4">
                        <form action="/instructor/room/{{ $roomCode }}/attendance" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 mr-2 rounded focus:outline-none focus:shadow-outline whitespace-nowrap">Create</button>
                        </form>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md">
                    <ul class="divide-y divide-gray-200">
                        @forelse($attendanceLists as $listId => $list)
                            @if(is_array($list))
                                <li class="p-4 flex flex-nowrap justify-between items-center hover:bg-gray-50 transition">
                                    <span class="text-gray-800 font-medium truncate">{{ $list['name'] }}</span>
                                    <a href="/instructor/room/{{ $roomCode }}/attendance/{{ $listId }}" class="flex-shrink-0 ml-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md text-sm">View</a>
                                </li>
                            @endif
                        @empty
                            <li class="p-4 text-center text-gray-500">No attendance lists created yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Students Panel -->
            <div id="panel-students" class="hidden">
                <div class="bg-white rounded-lg shadow-md">
                    <ul class="divide-y divide-gray-200">
                        @forelse($students as $studentId => $student)
                             <li class="p-4 flex items-center justify-between hover:bg-gray-50 transition">
                                <div class="flex-grow cursor-pointer student-entry" data-student-uid="{{ $studentId }}">
                                    <p class="font-semibold text-gray-800">{{ $student['firstName'] }} {{ $student['lastName'] }}</p>
                                    <p class="text-sm text-gray-500">ID: {{ $student['schoolId'] }}</p>
                                </div>
                                <button class="text-red-500 hover:text-red-700 p-2 rounded-full transition remove-student-btn" data-student-id="{{ $studentId }}" data-student-name="{{ $student['firstName'] }} {{ $student['lastName'] }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </li>
                        @empty
                            <li class="p-4 text-center text-gray-500">No students have joined this room yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.student-details-modal')

<!-- Remove Student Confirmation Modal -->
<div id="remove-student-modal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center md:block md:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true"><div class="absolute inset-0 bg-gray-500 opacity-75"></div></div>
        <span class="hidden md:inline-block md:align-middle md:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all md:my-8 md:align-middle md:max-w-lg md:w-full">
            <form id="remove-student-form" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="bg-white px-4 pt-5 pb-4 md:p-6 md:pb-4">
                    <div class="md:flex md:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 md:mx-0 md:h-10 md:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                        <div class="mt-3 text-center md:mt-0 md:ml-4 md:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Remove Student</h3>
                            <p class="text-sm text-gray-500">Are you sure you want to remove <strong id="student-name-to-remove"></strong>? This will remove them from the room and they will need to join again.</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 md:px-6 md:flex md:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 md:ml-3 md:w-auto md:text-sm">Remove</button>
                    <button type="button" id="cancel-remove-student-button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 md:mt-0 md:w-auto md:text-sm">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
<script src="{{ asset('js/StudentDetailsModal.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const students = @json($students);
        const studentDetailsModal = new StudentDetailsModal('student-details-modal', students);
        studentDetailsModal.initializeTriggers('.student-entry');

        // Tab functionality
        const attendanceTab = document.getElementById('tab-attendance');
        const studentsTab = document.getElementById('tab-students');
        const attendancePanel = document.getElementById('panel-attendance');
        const studentsPanel = document.getElementById('panel-students');

        const activeClasses = 'border-indigo-500 text-indigo-600';
        const inactiveClasses = 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300';
        
        function showAttendanceTab() {
            studentsTab.classList.remove(...activeClasses.split(' '));
            studentsTab.classList.add(...inactiveClasses.split(' '));
            attendanceTab.classList.add(...activeClasses.split(' '));
            attendanceTab.classList.remove(...inactiveClasses.split(' '));
            
            studentsPanel.classList.add('hidden');
            attendancePanel.classList.remove('hidden');
        }

        function showStudentsTab() {
            attendanceTab.classList.remove(...activeClasses.split(' '));
            attendanceTab.classList.add(...inactiveClasses.split(' '));
            studentsTab.classList.add(...activeClasses.split(' '));
            studentsTab.classList.remove(...inactiveClasses.split(' '));

            attendancePanel.classList.add('hidden');
            studentsPanel.classList.remove('hidden');
        }

        attendanceTab.addEventListener('click', showAttendanceTab);
        studentsTab.addEventListener('click', showStudentsTab);

        // Remove student modal
        const removeStudentModal = document.getElementById('remove-student-modal');
        const removeStudentForm = document.getElementById('remove-student-form');
        const cancelRemoveStudentButton = document.getElementById('cancel-remove-student-button');
        const studentNameToRemove = document.getElementById('student-name-to-remove');
        const removeStudentButtons = document.querySelectorAll('.remove-student-btn');

        removeStudentButtons.forEach(button => {
            button.addEventListener('click', () => {
                const studentId = button.dataset.studentId;
                const studentName = button.dataset.studentName;
                
                studentNameToRemove.textContent = studentName;
                removeStudentForm.action = `/instructor/room/{{ $roomCode }}/student/${studentId}/remove`;
                removeStudentModal.classList.remove('hidden');
            });
        });

        cancelRemoveStudentButton.addEventListener('click', () => {
            removeStudentModal.classList.add('hidden');
        });
    });
</script>
@endsection
