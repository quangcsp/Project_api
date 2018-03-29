<?php

namespace App\Http\Controllers\Api\Auth;

use App\Exceptions\Api\UnknownException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RefreshRequest;
use App\Contracts\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Fauth;

class LoginController extends ApiController
{
    use AuthenticatesUsers;
    protected $userRepository;
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->middleware('guest', ['except' => 'logout']);
        $this->userRepository = $userRepository;
    }

    public function login(LoginRequest $request)
    {
        $data = $request->only(['email', 'password']);
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            $currentUser = $this->userRepository->getCurrentUser1($data['email']);
            Auth::guard('fauth')->setUser($currentUser);

            $this->compacts['fauth'] = $currentUser;

            return $this->jsonRender();
        }
    }

    public function refreshToken(RefreshRequest $request)
    {
        $response = Fauth::driver(config('settings.default_provider'))->refreshToken($request->refresh_token);

        if (isset($response['error'])) {
            throw new UnknownException($response['error'], 404);
        }

        $this->compacts['fauth'] = $response;

        return $this->jsonRender();
    }
}
