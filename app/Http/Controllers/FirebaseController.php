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
            $user = $this->auth->createUserWithEmailAndPassword($request->email, $request->password);

            return response()->json([
                'message' => 'Signup successful!',
                'user' => $user,
            ]);
        } catch (EmailExists $e) {
            return response()->json([
                'message' => 'Email already exists.',
            ], 400);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'An error occurred during signup.',
                'error' => $e->getMessage(),
            ], 500);
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

            return response()->json([
                'message' => 'Login successful!',
                'data' => $signInResult->data(),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Invalid credentials.',
                'error' => $e->getMessage(),
            ], 401);
        }
    }
}
