<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RoomController extends Controller
{
    protected $database;

    public function __construct(FirebaseService $firebase)
    {
        $this->database = $firebase->getDatabase();
    }

    public function create()
    {
        return view('instructor.create-room');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'section' => 'required|string',
            'subject' => 'required|string',
        ]);

        $roomCode = Str::random(6);
        $instructorUid = session('user_uid');

        $this->database->getReference('rooms/' . $roomCode)->set([
            'name' => $request->name,
            'section' => $request->section,
            'subject' => $request->subject,
            'instructorUid' => $instructorUid,
        ]);

        return redirect('/instructor/dashboard')->with('success', 'Room created successfully!');
    }

    public function show($roomCode)
    {
        $room = $this->database->getReference('rooms/' . $roomCode)->getValue();

        if (!$room || $room['instructorUid'] !== session('user_uid')) {
            abort(403);
        }

        $students = [];
        if (isset($room['students'])) {
            $allUsers = $this->database->getReference('users')->getValue() ?? [];
            foreach ($room['students'] as $studentId => $value) {
                if (isset($allUsers[$studentId])) {
                    $students[$studentId] = $allUsers[$studentId];
                }
            }
        }

        $attendanceLists = $this->database->getReference('attendance/' . $roomCode)->getValue() ?? [];

        return view('instructor.room', [
            'room' => $room,
            'roomCode' => $roomCode,
            'students' => $students,
            'attendanceLists' => $attendanceLists
        ]);
    }

    public function createAttendanceList(Request $request, $roomCode)
    {
        $listId = Str::random(10);
        $date = Carbon::now()->toDateString();

        $this->database->getReference('attendance/' . $roomCode . '/' . $listId)->set([
            'name' => $date,
        ]);

        return redirect('/instructor/room/' . $roomCode . '/attendance/' . $listId)->with('success', 'Attendance list created successfully!');
    }

    public function showAttendance($roomCode, $listId)
    {
        $room = $this->database->getReference('rooms/' . $roomCode)->getValue();

        if (!$room || $room['instructorUid'] !== session('user_uid')) {
            abort(403);
        }

        $attendanceList = $this->database->getReference('attendance/' . $roomCode . '/' . $listId)->getValue();
        $students = $this->database->getReference('users')->getValue() ?? [];
        $attendance = $attendanceList['students'] ?? [];

        return view('instructor.attendance', [
            'room' => $room,
            'attendance' => $attendance,
            'students' => $students,
            'roomCode' => $roomCode,
            'listId' => $listId,
            'listName' => $attendanceList['name']
        ]);
    }

    public function updateAttendanceName(Request $request, $roomCode, $listId)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $this->database->getReference('attendance/' . $roomCode . '/' . $listId . '/name')
            ->set($request->name);

        return redirect('/instructor/room/' . $roomCode . '/attendance/' . $listId)->with('success', 'Attendance list name updated successfully!');
    }

    public function deleteAttendanceList($roomCode, $listId)
    {
        $this->database->getReference('attendance/' . $roomCode . '/' . $listId)->remove();

        return redirect('/instructor/room/' . $roomCode)->with('success', 'Attendance list deleted successfully!');
    }
}
