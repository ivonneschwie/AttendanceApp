@extends('layouts.app')

@section('content')
<div class="flex flex-col justify-center min-h-screen bg-gray-100 px-4 py-8">
    <div class="w-full max-w-4xl p-4 md:p-8 bg-white shadow-md rounded-lg mx-auto">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
            <h2 class="text-3xl font-bold mb-4 md:mb-0 text-center md:text-left">Room Details</h2>
            <a href="/instructor/dashboard" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full md:w-auto text-center">Back to Dashboard</a>
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
                 <h3 class="text-2xl font-bold mb-4">Students in this Room</h3>
                <div class="bg-white rounded-lg shadow-md">
                    <ul class="divide-y divide-gray-200">
                        @forelse($students as $studentId => $student)
                             <li class="p-4 flex items-center justify-between hover:bg-gray-50 transition cursor-pointer student-entry" data-student-uid="{{ $studentId }}">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $student['firstName'] }} {{ $student['lastName'] }}</p>
                                    <p class="text-sm text-gray-500">ID: {{ $student['schoolId'] }}</p>
                                </div>
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
    });
</script>
@endsection
