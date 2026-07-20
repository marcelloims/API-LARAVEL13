<?php

namespace App\Repositories\Auth;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;

class AuthRepository extends BaseRepository
{
    public function login($request)
    {
        $user = User::where('email', $request['email'])
            ->select('users.*')
            ->first();

        if (Hash::check($request['password'], $user->password)) {
            return true;
        } else {
            return false;
        }
    }
}
