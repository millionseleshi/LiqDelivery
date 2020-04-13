<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    protected $isAdmin = false;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->checkIsAdmin() == true) {
            return $next($request);
        }
        return new JsonResponse('unauthorized access', Response::HTTP_UNAUTHORIZED);
    }

    public function checkIsAdmin()
    {
        $user = Auth::user();
        if ($user->role == 'admin') {
            return $this->isAdmin = true;
        } else {
            return $this->isAdmin;
        }
    }
}
