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
            'listId' => 'required|string',
        ]);

        $studentUid = $request->studentUid;
        $roomCode = $request->roomCode;
        $listId = $request->listId;

        $this->database->getReference('attendance/' . $roomCode . '/' . $listId . '/students/' . $studentUid)->set(time());

        return response()->json(['success' => true]);
    }
}
