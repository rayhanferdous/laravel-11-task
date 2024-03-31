<?php

namespace App\Repositories\Auth;

interface AuthInterface
{
    public function login($data);
    public function register($data);
    public function logout();
    public function getUser($id);
    public function getUserByEmail($email);
}
