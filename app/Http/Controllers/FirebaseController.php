<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Database;
use Kreait\Firebase\Exception\Auth\EmailExists;

class FirebaseController extends Controller
{
    protected FirebaseAuth $auth;
    protected Database $database;

    public function __construct(FirebaseService $firebase)
    {
        $this->auth = $firebase->getAuth();
        $this->database = $firebase->getDatabase();
    }

    public function signup(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        try {
            $user = $this->auth->createUserWithEmailAndPassword($request->email, $request->password);
            session(['user_uid' => $user->uid]);

            return redirect('/onboarding');
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
            $userUid = $signInResult->firebaseUserId();
            $userData = $this->database->getReference('users/' . $userUid)->getValue();

            session([
                'user' => $signInResult->data(),
                'user_name' => $userData['firstName'] ?? ''
            ]);
            return redirect('/dashboard');
        } catch (\Throwable $e) {
            return back()->withErrors(['login_error' => 'Invalid credentials.']);
        }
    }

    public function dashboard()
    {
        if (session('user')) {
            $userName = session('user_name');
            return view('dashboard', ['userName' => $userName]);
        } else {
            return redirect('/');
        }
    }

    public function logout()
    {
        session()->forget('user');
        session()->forget('user_uid');
        session()->forget('user_name');
        return redirect('/');
    }

    public function onboarding()
    {
        return view('onboarding');
    }

    public function storeOnboardingData(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'middle_initial' => 'nullable|string|max:1',
            'school_id' => 'required|string',
        ]);

        $userUid = session('user_uid');

        if (!$userUid) {
            return redirect('/')->withErrors(['message' => 'User not authenticated.']);
        }

        $this->database->getReference('users/' . $userUid)->set([
            'firstName' => $request->first_name,
            'lastName' => $request->last_name,
            'middleInitial' => $request->middle_initial,
            'schoolId' => $request->school_id,
        ]);

        session()->forget('user_uid');

        return redirect('/')->with('success', 'Success! Please log in.');
    }
}
