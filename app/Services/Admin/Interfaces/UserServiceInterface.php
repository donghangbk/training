<?php

namespace App\Services\Admin\Interfaces;

interface UserServiceInterface {
    public function getUsers($paginate = true);
    public function createUser(array $data);
    public function updateUser($user, array $data);
    public function getInfoUser();
    public function updateProfile(array $data);
    public function insightUser();
}