<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|max:255',
            'email' => 'bail|required|email|max:255|unique:admins',
            'password' => 'bail|required|string|min:8|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 'fail',
                "data" => $validator->errors(),
            ], 401);
        }

        $validated = $validator->validated();
        $validated['password'] = Hash::make($validated['password']);

        DB::beginTransaction();
        try {
            User::create($validated);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                "status" => 'error',
                "data" => 'error server',
            ], 500);
        }

        return response()->json([
            "status" => "success",
            "data" => null,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'bail|required|email|max:255|exists:users',
            'password' => 'bail|required|string|min:8|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 'fail',
                "data" => $validator->errors(),
            ], 401);
        }

        $validated = $validator->validated();

        if (Auth::guard('web')->attempt($validated)) {
            if ($request->hasSession()) {
                $request->session()->regenerate();
            }
            $token = $request->user()->createToken($request->email . '' . now());

            return response()->json([
                "status" => 'success',
                "data" => [
                    'token' => $token->plainTextToken
                ],
            ]);
        }

        return response()->json([
            "status" => 'fail',
            "message" => 'Wrong account or password',
        ], 400);
    }
}
