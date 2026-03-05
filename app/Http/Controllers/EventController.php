<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Kreait\Firebase\Exception\Auth\UserNotFound;

class EventController extends Controller
{
    protected $database;

    public function __construct(FirebaseService $firebase)
    {
        $this->database = $firebase->getDatabase();
    }

    public function create()
    {
        return view('instructor.create-event');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $eventName = $request->name;
        $eventRef = $this->database->getReference('events')->push();
        $eventRef->set([
            'name' => $eventName,
            'instructorUid' => session('user_uid'),
        ]);

        return redirect('/instructor/events');
    }

    public function index()
    {
        $events = $this->database->getReference('events')->getValue() ?? [];
        return view('instructor.events', ['events' => $events]);
    }

    public function show($eventId)
    {
        return redirect('/instructor/event/' . $eventId . '/scan');
    }

    public function createAttendanceList(Request $request, $eventId)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $listName = $request->name;
        $listRef = $this->database->getReference('event-attendance/' . $eventId . '/lists')->push();
        $listRef->set([
            'name' => $listName,
        ]);

        return redirect('/instructor/event/' . $eventId);
    }

    public function scan($eventId)
    {
        $event = $this->database->getReference('events/' . $eventId)->getValue();
        $attendanceData = $this->database->getReference('event-attendance/' . $eventId . '/students')->getValue() ?? [];
        
        $studentUids = array_keys($attendanceData);
        $students = [];

        if(!empty($studentUids)) {
            $users = $this->database->getReference('users')->getValue();
            foreach($studentUids as $uid) {
                if(isset($users[$uid])) {
                    $students[$uid] = $users[$uid];
                }
            }
        }
        
        return view('instructor.event-scan', [
            'event' => $event,
            'eventId' => $eventId,
            'attendance' => $attendanceData,
            'students' => $students
        ]);
    }

    public function delete($eventId)
    {
        $this->database->getReference('events/' . $eventId)->remove();
        $this->database->getReference('event-attendance/' . $eventId)->remove();

        return redirect('/instructor/events')->with('success', 'Event deleted successfully.');
    }

    public function update(Request $request, $eventId)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $this->database->getReference('events/' . $eventId)->update([
            'name' => $request->name,
        ]);

        return redirect('/instructor/event/' . $eventId . '/scan')->with('success', 'Event updated successfully.');
    }
}
