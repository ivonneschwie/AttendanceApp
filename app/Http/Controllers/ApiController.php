<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    protected $database;

    public function __construct(FirebaseService $firebase)
    {
        $this->database = $firebase->getDatabase();
    }

    public function markAttendance(Request $request)
    {
        $request->validate([
            'studentUid' => 'required|string',
            'roomCode' => 'required|string',
        ]);

        $studentUid = $request->studentUid;
        $roomCode = $request->roomCode;

        $this->database->getReference('attendance/' . $roomCode . '/' . $studentUid)->set(time());

        return response()->json(['success' => true]);
    }
}
