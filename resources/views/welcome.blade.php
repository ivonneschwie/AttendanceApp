@extends('layouts.app')

@section('content')
<div class="min-h-screen flex">
    <!-- Left Pane -->
    <div class="hidden lg:flex flex-col justify-center w-1/2 bg-blue-900 text-white p-8">
        <div class="text-center">
            <div class="flex flex-col items-center mb-6">
                <svg class="w-20 h-20 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h1 class="text-5xl font-bold">UDD QR Attendance</h1>
            </div>
            <p class="text-xl">Student Teacher Educational Portal. Boost Your Learning to New Heights.</p>
        </div>
    </div>

    <!-- Right Pane -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
        <div class="w-full max-w-md">

            <!-- Login Form -->
            <div id="login-form">
                <h2 class="text-3xl font-bold mb-2">Welcome back</h2>
                <p class="text-gray-600 mb-8">Please enter your details to sign in.</p>

                @if(session('success'))
                    <div class="notification-message bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block md:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if($errors->has('login_error'))
                    <div class="notification-message bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-2" role="alert">
                        <ul>
                            <li>{{ $errors->first('login_error') }}</li>
                        </ul>
                    </div>
                @endif

                <form action="/login" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                            Email Address
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206z"></path></svg>
                            </span>
                            <input class="appearance-none border rounded w-full py-2 px-3 pl-10 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" name="email" placeholder="Email" required>
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                            Password
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </span>
                            <input class="appearance-none border rounded w-full py-2 px-3 pl-10 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" name="password" placeholder="******************" required>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mb-6">
                        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                            Sign In
                        </button>
                    </div>
                </form>
                <p class="text-center text-gray-600" id="signup-prompt">
                    Don't have an account? <a href="#" onclick="showSignup()" class="text-blue-600 hover:underline">Signup now</a>
                </p>
            </div>

            <!-- Signup Form (hidden by default) -->
            <div id="signup-form" class="hidden">
                 <h2 class="text-3xl font-bold mb-2">Create an account</h2>
                <p class="text-gray-600 mb-8">Please enter your details to sign up.</p>

                 @if ($errors->any() && !$errors->has('login_error'))
                    <div class="notification-message bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-2" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="/signup" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="signup-email">
                            Email Address
                        </label>
                        <input class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="signup-email" type="email" name="email" placeholder="Email" required value="{{ old('email') }}">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="signup-password">
                            Password
                        </label>
                        <input class="appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="signup-password" type="password" name="password" placeholder="******************" required>
                    </div>
                    <div class="flex items-center justify-between mb-6">
                        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                            Sign Up
                        </button>
                    </div>
                </form>
                 <p class="text-center text-gray-600" id="login-prompt">
                    Already have an account? <a href="#" onclick="showLogin()" class="text-blue-600 hover:underline">Login now</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    function showSignup() {
        document.getElementById('login-form').classList.add('hidden');
        document.getElementById('signup-form').classList.remove('hidden');
    }

    function showLogin() {
        document.getElementById('signup-form').classList.add('hidden');
        document.getElementById('login-form').classList.remove('hidden');
    }

    // If there are signup errors, show the signup form by default
    @if ($errors->any() && !$errors->has('login_error'))
        showSignup();
    @endif
</script>
@endsection
