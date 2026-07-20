<?php

namespace App\Services\Auth;

use App\Mail\WelcomeEmail;
use App\Models\User;
use App\Repositories\Auth\AuthRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function login($request)
    {
        $check = $this->authRepository->login($request->only(['email', 'password']));

        if (!$check) {
            return false;
        }

        if (!$token = auth()->attempt($request->only(['email', 'password']))) {
            return false;
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
        ];
    }

    public function register($request)
    {
        $data = array_merge(
            $request->validated(),
            [
                'email_verified_at' => now(),
                'password' => bcrypt($request->password),
            ],
            $this->authRepository->auditableCreate()
        );

        if ($this->authRepository->store(User::class, $data)) {
            Mail::to($request->email)->send(new WelcomeEmail($request->name));
            return response()->json([
                'success' => true,
                'message' => 'Register Successful'
            ], Response::HTTP_CREATED);
        }
    }

    public function userDetail($id)
    {
        return $this->authRepository->getDataById(User::class, $id, ['id','name','email','created_at']);
    }
}

