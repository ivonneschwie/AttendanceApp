@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-4xl p-8 bg-white shadow-md rounded-lg">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold">Welcome, Admin!</h2>
            <div>
                <a href="/admin/register-instructor" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2">Register Instructor</a>
                <a href="/logout" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Logout</a>
            </div>
        </div>

        <h3 class="text-2xl font-bold mb-4">Registered Users</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="w-full bg-gray-200 text-left text-sm font-semibold text-gray-700">
                        <th class="py-3 px-4">First Name</th>
                        <th class="py-3 px-4">Last Name</th>
                        <th class="py-3 px-4">Middle Initial</th>
                        <th class="py-3 px-4">School ID</th>
                        <th class="py-3 px-4">Type</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach($users as $user)
                        <tr class="border-b border-gray-200">
                            <td class="py-3 px-4">{{ $user['firstName'] }}</td>
                            <td class="py-3 px-4">{{ $user['lastName'] }}</td>
                            <td class="py-3 px-4">{{ $user['middleInitial'] ?? '' }}</td>
                            <td class="py-3 px-4">{{ $user['schoolId'] ?? '' }}</td>
                            <td class="py-3 px-4">{{ $user['type'] ?? 'student' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
