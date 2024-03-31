<?php

namespace App\Http\Controllers;

use App\Repositories\Auth\AuthInterface;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    use ApiResponseTrait;

    private AuthInterface $repository;

    public function __construct(AuthInterface $repository)
    {
        $this->repository = $repository;
    }

    public function login(Request $request): JsonResponse
    {

        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $token = $this->repository->login($request);

        if (!$token) {
            return $this->ResponseError('Invalid Credentials', null, 'Invalid Credentials', 401);
        }

        $user = $this->repository->getUserByEmail($request->email);

        $data = [
            'token' => $token,
            'user'  => $user,
        ];

        return $this->ResponseSuccess(['data' => $data], 'Login Successful', "login Successful", 200);
    }

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users|max:255',
            'password'          => 'required|string|min:6',
            'confirm_password'  => 'required|same:password',
            'address_bn'        => 'nullable|string',
            'address_en'        => 'nullable|string',
        ]);

        $user = $this->repository->register($request);

        if (!$user) {
            return $this->ResponseError('Registration Failed', null, 'Registration Failed', 400);
        }

        $user = $this->repository->getUser($user->id);

        $data = [
            'user' => $user,
        ];

        return $this->ResponseSuccess($data, 'Registration Successful', 200);
    }

    public function logout(): JsonResponse
    {
        $this->repository->logout();
        return $this->ResponseSuccess([], 'Logout Successful', 200);
    }
}
