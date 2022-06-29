<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Api\ApiController;
use App\Services\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends ApiController
{
    private $userServiceInterface;

    public function __construct(UserServiceInterface $userServiceInterface) {
        $this->userServiceInterface = $userServiceInterface;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        list($datas, $status) = $this->userServiceInterface->register($request->all());

        return $this->response($datas, $status);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        list($datas, $status) = $this->userServiceInterface->login($request->all());

        return $this->response($datas, $status);
    }

    public function logout()
    {
        list($datas, $status) = $this->userServiceInterface->logout();

        return $this->response($datas, $status);
    }
}
