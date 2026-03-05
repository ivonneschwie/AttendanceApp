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

        $roomStudentsRef = $this->database->getReference('rooms/' . $roomCode . '/students/');
        $roomStudents = $roomStudentsRef->getValue();

        if (!isset($roomStudents[$studentUid])) {
            return response()->json(['success' => false, 'message' => 'Invalid student']);
        }
        
        $this->database->getReference('attendance/' . $roomCode . '/' . $listId . '/students/' . $studentUid)->set(time());

        return response()->json(['success' => true, 'message' => 'Successfully scanned']);
    }

    public function markEventAttendance(Request $request)
    {
        $request->validate([
            'studentUid' => 'required|string',
            'eventId' => 'required|string',
        ]);

        $studentUid = $request->studentUid;
        $eventId = $request->eventId;

        $userRef = $this->database->getReference('users/' . $studentUid);
        $user = $userRef->getValue();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Invalid student']);
        }

        $this->database->getReference('event-attendance/' . $eventId . '/students/' . $studentUid)->set(time());

        return response()->json(['success' => true, 'message' => 'Successfully scanned']);
    }
}
