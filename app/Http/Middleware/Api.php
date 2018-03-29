<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Eloquent\User;
use Fauth;
use App\Exceptions\Api\UnknownException;
use App\Contracts\Repositories\UserRepository;

class Api
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $email = $request->header('email');
        $currentUser = $this->userRepository->getCurrentUser1($email);
        Auth::guard('fauth')->setUser($currentUser);

        return $next($request);
    }
}
