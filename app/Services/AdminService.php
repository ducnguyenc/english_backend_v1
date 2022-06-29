<?php

namespace App\Services;

use App\Repositories\AdminRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdminService extends BaseService implements AdminServiceInterface
{
    private $adminRepositoryInterface;

    public function __construct(AdminRepositoryInterface $adminRepositoryInterface)
    {
        $this->adminRepositoryInterface = $adminRepositoryInterface;
    }

    private function validate($validator)
    {
        if ($validator->fails()) {
            return [false, $validator->errors()];
        }

        return [true, $validator];
    }

    private function validateRegister($params)
    {
        $validator = Validator::make($params, [
            'name' => 'bail|required|string|max:255',
            'email' => 'bail|required|email|max:255|unique:admins',
            'password' => 'bail|required|string|min:8|max:255',
        ]);

        return $this->validate($validator);
    }

    public function register($params)
    {
        list($status, $data) = $this->validateRegister($params);
        if (!$status) {
            return [$this->responseFail($data), Response::HTTP_UNAUTHORIZED];
        }

        $validated = $data->validated();
        $validated['password'] = Hash::make($validated['password']);

        DB::beginTransaction();
        try {
            $admin = $this->adminRepositoryInterface->create($validated);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return [$this->responseError('error server'), Response::HTTP_INTERNAL_SERVER_ERROR];
        }

        return [$this->responseSuccess($admin), Response::HTTP_OK];
    }

    public function validateLogin($params)
    {
        $validator = Validator::make($params, [
            'email' => 'bail|required|email|max:255|exists:admins',
            'password' => 'bail|required|string|min:8|max:255',
        ]);

        return $this->validate($validator);
    }

    public function login($params)
    {
        list($status, $data) = $this->validateLogin($params);
        if (!$status) {
            return [$this->responseFail($data), Response::HTTP_UNAUTHORIZED];
        }

        $validated = $data->validated();

        if (Auth::guard('admin')->attempt($validated)) {
            $accessToken = Auth::guard('admin')->user()->createToken($validated['email'] . '' . now())->plainTextToken;

            return [$this->responseSuccess($accessToken), Response::HTTP_OK];
        }

        $data = ['error' => 'Wrong account or password'];
        return [$this->responseFail($data), Response::HTTP_UNAUTHORIZED];
    }

    public function logout($params)
    {
        try {
            request()->user()->tokens()->delete();
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return [$this->responseError('error server'), Response::HTTP_INTERNAL_SERVER_ERROR];
        }

        return [$this->responseSuccess(), Response::HTTP_OK];
    }
}
