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
        if ($request->has('event_id')) {
            $request->validate([
                'qr_code' => 'required|string',
                'event_id' => 'required|string',
            ]);
    
            $studentUid = $request->qr_code;
            $eventId = $request->event_id;
    
            $student = $this->database->getReference('users/' . $studentUid)->getValue();
    
            if (!$student) {
                return response()->json(['success' => false, 'message' => 'Invalid student QR code.']);
            }
    
            $this->database->getReference('event_attendance/' . $eventId . '/' . $studentUid)->set([
                'name' => $student['name'],
                'timestamp' => round(microtime(true) * 1000)
            ]);
    
            return response()->json(['success' => true, 'message' => 'Event attendance marked successfully.']);
        }

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
}
