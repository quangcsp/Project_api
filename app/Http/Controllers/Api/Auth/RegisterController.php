<?php

namespace App\Http\Controllers\Api\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Contracts\Repositories\UserRepository;
use App\Http\Controllers\Api\ApiController;
use App\Contracts\Services\PassportInterface;
use Illuminate\Database\QueryException;
use App\Exceptions\Api\NotQueryException;
use App\Exceptions\Api\NotFoundErrorException;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\Auth\RegisterRequest;
use Fauth;

class RegisterController extends ApiController
{
    public function __construct(UserRepository $userRepository)
    {
        $this->middleware('guest');
        $this->userRepository = $userRepository;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */

    protected function register(RegisterRequest $request)
    {
        try {
            $user = $this->userRepository->create($request->all());
            Auth::guard('fauth')->setUser($user);

            $this->compacts['fauth'] = $user;

            return $this->jsonRender();
        } catch (Exception $e) {
            return -1;
        }
    }

}
