@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center h-screen">
    <div class="container mx-auto p-4">

        <div class="w-full max-w-md mx-auto">
            <div class="flex border-b">
                <button id="login-tab" class="py-2 px-4 rounded-tl-md font-semibold @if($errors->has('login_error') || !$errors->any()) bg-white text-gray-500 border-b-2 border-blue-500 @else bg-gray-200 text-gray-500 @endif" onclick="showTab('login')">Login</button>
                <button id="signup-tab" class="py-2 px-4 rounded-tr-md font-semibold @if($errors->has('login_error') || !$errors->any()) bg-gray-200 text-gray-500 @else bg-white text-gray-500 border-b-2 border-blue-500 @endif" onclick="showTab('signup')">Signup</button>
            </div>

            <div id="login" class="p-4 bg-white shadow-xl rounded-tr-xl rounded-b-xl @if($errors->has('login_error') || !$errors->any()) @else hidden @endif">
                <h2 class="text-2xl font-bold pt-2 mb-4 text-center">Login</h2>
                @if(session('success'))
                    <div class="notification-message bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if($errors->has('login_error'))
                    <div class="notification-message bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-2" role="alert">
                        <ul>
                            <li>{{ $errors->first('login_error') }}</li>
                        </ul>
                    </div>
                @endif
                <form action="/login" method="POST" class="bg-white rounded pt-2 pb-6">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="login-email">
                            Email
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="login-email" type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="login-password">
                            Password
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="login-password" type="password" name="password" placeholder="******************" required>
                    </div>
                    <div class="flex items-center justify-between">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                            Login
                        </button>
                    </div>
                </form>
            </div>

            <div id="signup" class="p-4 bg-white shadow-xl rounded-tr-xl rounded-b-xl @if($errors->has('login_error') || !$errors->any()) hidden @endif">
                <h2 class="text-2xl font-bold pt-2 mb-4 text-center">Signup</h2>
                 @if ($errors->any() && !$errors->has('login_error'))
                    <div class="notification-message bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-2" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="/signup" method="POST" class="bg-white rounded pt-2 pb-6">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="signup-email">
                            Email
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="signup-email" type="email" name="email" placeholder="Email" required value="{{ old('email') }}">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="signup-password">
                            Password
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="signup-password" type="password" name="password" placeholder="******************" required>
                    </div>
                    <div class="flex items-center justify-between">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                            Signup
                        </button
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function showTab(tabName) {
        let signupTab = document.getElementById('signup');
        let loginTab = document.getElementById('login');
        let signupTabButton = document.getElementById('signup-tab');
        let loginTabButton = document.getElementById('login-tab');
        
        let notifications = document.querySelectorAll('.notification-message');
        notifications.forEach(function(notification) {
            notification.style.display = 'none';
        });

        if (tabName === 'signup') {
            signupTab.classList.remove('hidden');
            loginTab.classList.add('hidden');
            signupTabButton.classList.add('border-b-2', 'border-blue-500', 'bg-white');
            signupTabButton.classList.remove('bg-gray-200');
            loginTabButton.classList.remove('border-b-2', 'border-blue-500', 'bg-white');
            loginTabButton.classList.add('bg-gray-200');
        } else {
            loginTab.classList.remove('hidden');
            signupTab.classList.add('hidden');
            loginTabButton.classList.add('border-b-2', 'border-blue-500', 'bg-white');
            loginTabButton.classList.remove('bg-gray-200');
            signupTabButton.classList.remove('border-b-2', 'border-blue-500', 'bg-white');
            signupTabButton.classList.add('bg-gray-200');
        }
    }
</script>
@endsection