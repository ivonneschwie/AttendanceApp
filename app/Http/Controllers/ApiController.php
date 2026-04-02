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
            return response()->json(['success' => false, 'message' => 'Invalid student for this room']);
        }
        
        $attendanceRef = $this->database->getReference('attendance/' . $roomCode . '/' . $listId . '/students/' . $studentUid);
        $attendanceEntry = $attendanceRef->getValue();

        if (is_array($attendanceEntry) && isset($attendanceEntry['time_in'])) {
            // Already timed in. This scan is for time-out.
            if (!isset($attendanceEntry['time_out']) || $attendanceEntry['time_out'] === null) {
                $attendanceRef->update(['time_out' => time()]);
                return response()->json(['success' => true, 'message' => 'Successfully timed out']);
            } else {
                // If user scans again after timing out, update time-out.
                $attendanceRef->update(['time_out' => time()]);
                return response()->json(['success' => true, 'message' => 'Time out updated']);
            }
        } else {
            // First scan. This is a time-in.
            $attendanceRef->set([
                'time_in' => time(),
                'time_out' => null
            ]);
            return response()->json(['success' => true, 'message' => 'Successfully timed in']);
        }
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

        $attendanceRef = $this->database->getReference('event-attendance/' . $eventId . '/students/' . $studentUid);
        $attendanceEntry = $attendanceRef->getValue();

        if (is_array($attendanceEntry) && isset($attendanceEntry['time_in'])) {
            // Already timed in. This scan is for time-out.
            if (!isset($attendanceEntry['time_out']) || $attendanceEntry['time_out'] === null) {
                $attendanceRef->update(['time_out' => time()]);
                return response()->json(['success' => true, 'message' => 'Successfully timed out']);
            } else {
                // If user scans again after timing out, update time-out.
                $attendanceRef->update(['time_out' => time()]);
                return response()->json(['success' => true, 'message' => 'Time out updated']);
            }
        } else {
            // First scan. This is a time-in.
            $attendanceRef->set([
                'time_in' => time(),
                'time_out' => null,
            ]);
            return response()->json(['success' => true, 'message' => 'Successfully timed in']);
        }
    }
}
