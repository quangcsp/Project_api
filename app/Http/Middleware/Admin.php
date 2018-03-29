<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Eloquent\User;
use Fauth;
use App\Exceptions\Api\UnknownException;
use App\Exceptions\Api\NotAdminException;
use App\Contracts\Repositories\UserRepository;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle($request, Closure $next)
    {
        $accessToken = $request->header('Authorization');
        $userFromAuthServer = Fauth::driver(config('settings.default_provider'))->getUserByToken($accessToken);

        if (!$userFromAuthServer) {
            throw new UnknownException(translate('http_message.unauthorized'), 401);
        }

        $currentUser = $this->userRepository->getCurrentUser($userFromAuthServer);
        Auth::guard('fauth')->setUser($currentUser);

        if ($currentUser->role != config('settings.admin')) {
            throw new UnknownException(translate('exception.not_admin'), 401);
        }

        return $next($request);
    }
}
