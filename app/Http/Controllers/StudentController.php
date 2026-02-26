<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected $database;

    public function __construct(FirebaseService $firebase)
    {
        $this->database = $firebase->getDatabase();
    }

    public function showJoinRoomForm()
    {
        return view('student.join-room');
    }

    public function joinRoom(Request $request)
    {
        $request->validate([
            'room_code' => 'required|string',
        ]);

        $roomCode = $request->room_code;
        $studentUid = session('user_uid');

        $room = $this->database->getReference('rooms/' . $roomCode)->getValue();

        if (!$room) {
            return back()->withErrors(['room_code' => 'Room not found.']);
        }

        $this->database->getReference('rooms/' . $roomCode . '/students/' . $studentUid)->set(true);

        return redirect('/dashboard')->with('success', 'Successfully joined the room!');
    }

    public function show($roomCode)
    {
        $studentUid = session('user_uid');
        $room = $this->database->getReference('rooms/' . $roomCode)->getValue();

        if (!$room || !isset($room['students']) || !array_key_exists($studentUid, $room['students'])) {
            return redirect('/dashboard')->withErrors(['message' => 'You are not a member of this room.']);
        }

        return view('student.show', ['room' => $room]);
    }

    public function showScan()
    {
        return view('student.scan');
    }

    public function scan(Request $request)
    {
        $request->validate([
            'room_code' => 'required|string',
        ]);

        $roomCode = $request->room_code;
        $studentUid = session('user_uid');

        $room = $this->database->getReference('rooms/' . $roomCode)->getValue();

        if (!$room) {
            return redirect('/dashboard')->withErrors(['message' => 'Room not found.']);
        }

        $this->database->getReference('attendance/' . $roomCode . '/students/' . $studentUid)->set(true);

        return redirect('/dashboard')->with('success', 'Attendance marked successfully!');
    }
}
