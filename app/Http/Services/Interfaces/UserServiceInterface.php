<?php
namespace App\Services\Interfaces;
use Illuminate\Http\Request;
Interface UserServiceInterface {

    public function listUser();
    public function formCreate();
    public function createUser(Request $request);
    public function formEdit($id);
    public function updateUser($request, $id);
    public function userProfile();
    public function updateUserProfile($request);
    public function getSetting();
    public function updateSetting($request);
}