<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Illuminate\Support\Facades\Session;

class EventController extends Controller
{
    private $database;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(storage_path(\'app/firebase/laravel-qr-attendance-firebase-adminsdk-j33ft-173359c63c.json\'))
                               ->withDatabaseUri(\'https://laravel-qr-attendance-default-rtdb.firebaseio.com/\');
        $this->database = $factory->createDatabase();
    }

    public function index()
    {
        $userId = Session::get(\'user\')[\'uid\'];
        $eventsRef = $this->database->getReference(\'events\')->orderByChild(\'instructor_id\')->equalTo($userId);
        $events = $eventsRef->getValue();

        return view(\'events.index\', compact(\'events\'));
    }

    public function create()
    {
        return view(\'events.create\');
    }

    public function store(Request $request)
    {
        $request->validate([
            \'name\' => \'required|string|max:255\',
            \'date\' => \'required|date\',
        ]);

        $userId = Session::get(\'user\')[\'uid\'];

        $this->database->getReference(\'events\')->push([
            \'name\' => $request->name,
            \'date\' => $request->date,
            \'instructor_id\' => $userId,
        ]);

        return redirect()->route(\'events.index\')->with(\'success\', \'Event created successfully.\');
    }

    public function show($eventId)
    {
        $event = $this->database->getReference(\'events/\'.$eventId)->getValue();
        $attendance = $this->database->getReference(\'event_attendance/\'.$eventId)->getValue();

        return view(\'events.show\', compact(\'event\', \'attendance\', \'eventId\'));
    }

    public function scan($eventId)
    {
        $event = $this->database->getReference(\'events/\'.$eventId)->getValue();
        return view(\'events.scan\', compact(\'event\', \'eventId\'));
    }
}
