<?php

namespace App\Http\Middleware;

use App\Models\User as ModelsUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class User
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() instanceof ModelsUser) {
            return $next($request);
        }

        $headers['Content-Type'] = 'application/json';
        return response()->json(
            [
                'status' => 'error',
                'message' => 'wrong access_token'
            ],
            Response::HTTP_BAD_REQUEST,
            $headers
        );
    }
}
