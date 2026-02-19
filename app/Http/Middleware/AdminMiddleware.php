<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\FirebaseService;

class AdminMiddleware
{
    protected $database;

    public function __construct(FirebaseService $firebase)
    {
        $this->database = $firebase->getDatabase();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userUid = session('user_uid');

        if ($userUid) {
            $userData = $this->database->getReference('users/' . $userUid)->getValue();
            if (isset($userData['type']) && $userData['type'] === 'admin') {
                return $next($request);
            }
        }

        return redirect('/');
    }
}
