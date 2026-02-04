<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Database;
use Kreait\Firebase\Messaging;

class FirebaseService
{
    protected Auth $auth;
    protected Database $database;
    protected Messaging $messaging;

    public function __construct()
    {
        $factory = (new Factory)
            //Path to service account file
            ->withServiceAccount(storage_path('app/firebase/firebase-credentials.json'))
            //Change This to firebase realtime database path
            ->withDatabaseUri('https://qr-attendanceapp-default-rtdb.asia-southeast1.firebasedatabase.app');

        $this->auth = $factory->createAuth();
        $this->database = $factory->createDatabase();
        $this->messaging = $factory->createMessaging();
    }

    public function getAuth(): Auth
    {
        return $this->auth;
    }

    public function getDatabase(): Database
    {
        return $this->database;
    }

    public function getMessaging(): Messaging
    {
        return $this->messaging;
    }
}
