<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\Auth\EmailExists;

class FirebaseController extends Controller
{
    protected FirebaseAuth $auth;

    public function __construct(FirebaseService $firebase)
    {
        $this->auth = $firebase->getAuth();
    }

    public function signup(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        try {
            $this->auth->createUserWithEmailAndPassword($request->email, $request->password);

            return redirect('/')->with('success', 'Account created successfully! Please login.');
        } catch (EmailExists $e) {
            return back()->withErrors(['email' => 'Email already exists.']);
        } catch (\Throwable $e) {
            return back()->withErrors(['message' => 'An error occurred during signup.']);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $signInResult = $this->auth->signInWithEmailAndPassword($request->email, $request->password);
            session(['user' => $signInResult->data()]);
            return redirect('/dashboard');
        } catch (\Throwable $e) {
            return back()->withErrors(['message' => 'Invalid credentials.']);
        }
    }

    public function dashboard()
    {
        if (session('user')) {
            return view('dashboard');
        } else {
            return redirect('/');
        }
    }

    public function logout()
    {
        session()->forget('user');
        return redirect('/');
    }
}
