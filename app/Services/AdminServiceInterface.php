<?php

namespace App\Services;

interface AdminServiceInterface
{
    public function register($params);
    public function login($params);
    public function logout($params);
}
