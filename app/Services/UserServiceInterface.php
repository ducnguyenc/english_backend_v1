<?php

namespace App\Services;

interface UserServiceInterface
{
    public function register($params);
    public function login($params);
    public function logout();
}
