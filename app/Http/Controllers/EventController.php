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
            'instructorUid' => session('user')['uid'],
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
        $event = $this->database->getReference('events/' . $eventId)->getValue();
        $attendance = $this->database->getReference('event-attendance/' . $eventId . '/students')->getValue();
        $students = [];

        if ($attendance) {
            foreach ($attendance as $studentUid => $timestamp) {
                try {
                    $user = app(FirebaseService::class)->getAuth()->getUser($studentUid);
                    $students[] = [
                        'uid' => $studentUid,
                        'name' => $user->displayName ?? 'N/A',
                        'email' => $user->email,
                        'timestamp' => $timestamp,
                    ];
                } catch (UserNotFound $e) {
                    // Handle case where user is not found
                }
            }
        }
        
        return view('instructor.event-attendance', ['event' => $event, 'students' => $students, 'eventId' => $eventId]);
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
}
