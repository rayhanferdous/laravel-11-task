<?php

namespace App\Repositories\Auth;

use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthRepo implements AuthInterface
{
    public function login($data)
    {
        $credentials = $data->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return false;
        }

        return $token;
    }

    public function register($data)
    {
        $user           = new User();
        $user->email    = $data->email;
        $user->password = $data->password;
        $user->status   = 'inactive';
        $user->save();
        return $user;
    }


    public function getUser($id)
    {
        $user = User::find($id);
        return $user;
    }

    public function getUserByEmail($email)
    {
        $user = User::where('email', $email)->first();
        return $user;
    }

    public function logout()
    {
        try {
            $token = JWTAuth::getToken();
            JWTAuth::invalidate($token);
        } catch (JWTException $e) {
        }

        return true;
    }
}
