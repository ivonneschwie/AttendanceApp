<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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

        return view('instructor.room', [
            'room' => $room,
            'roomCode' => $roomCode,
            'students' => $students
        ]);
    }

    public function showAttendance($roomCode)
    {
        $room = $this->database->getReference('rooms/' . $roomCode)->getValue();

        if (!$room || $room['instructorUid'] !== session('user_uid')) {
            abort(403);
        }

        $attendance = $this->database->getReference('attendance/' . $roomCode)->getValue() ?? [];
        $students = $this->database->getReference('users')->getValue() ?? [];

        return view('instructor.attendance', [
            'room' => $room,
            'attendance' => $attendance,
            'students' => $students,
            'roomCode' => $roomCode
        ]);
    }
}
