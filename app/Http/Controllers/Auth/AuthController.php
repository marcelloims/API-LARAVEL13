<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        try {
            $result = $this->authService->login($request);

            if ($result === false) {
                return $this->errorResponse('User Invalid', 401);
            }else {
                $token = $result['access_token'];
                $cookie = cookie(
                    'access_token',
                    $token,
                    60,
                    '/',
                    null,
                    true,
                    true,
                    false,
                    'Lax'
                );

                return $this->successResponse($result, 'Login successful')->withCookie($cookie);
            }

        } catch (\Throwable $e) {
            return $this->errorResponse('errors', 500, $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successful'
        ]);
    }

    public function register(RegisterRequest $request)
    {
        try {
            return $this->authService->register($request);
        } catch (\Throwable $e) {
            return $this->errorResponse('errors', 500, $e->getMessage());
        }
    }

    public function userDetail($id)
    {
        try {
            return new UserResource($this->authService->userDetail($id));
        } catch (\Throwable $e) {
            return $this->errorResponse('errors', 500, $e->getMessage());
        }
    }
}
