<?php

namespace App\Services\Admin;

use File;
use App\Models\User;
use App\Models\Timesheet;
use Illuminate\Http\Request;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;
use App\Services\Admin\Interfaces\UserServiceInterface;

class UserService implements UserServiceInterface
{
    /**
     * get users with order by id desc
     *
     * @param boolean $paginate
     * @return Collection
     */
    public function getUsers($paginate = false)
    {
        $users = User::orderBy('id', 'desc')->active()->role(User::ROLE_USER);

        if ($paginate) {
            return $users->paginate(config('timesheet.paginate'));
        }

        return $users->get();
    }

    /**
     * create new user
     *
     * @param array $data
     * @return boolean|User
     */
    public function createUser(array $data)
    {
        if (data_get($data, 'image')) {
            $img = $this->uploadAvatar($data['image'], $data['username']);
            $data['avatar'] = $img;
        }

        // create user
        $user = User::create([
            'username' => $data['username'],
            'email'    => $data['email'],
            'role_id'  => User::ROLE_USER,
            'password' => \Hash::make(config('timesheet.pass_default')),
            'avatar'   => $data['avatar'] ?? config('timesheet.avatar')
        ]);
        return $user;
    }

    public function registReceiverNotification($user, array $receivers)
    {
        $data = [];
        foreach ($receivers as $receiver) {
            $data[] = [
                'user_receive_id' => $receiver
            ];
        }

        return $user->notifications()->createMany($data);
    }

    /**
     * update user
     *
     * @param User|int $user
     * @param array $data
     * @return void
     */
    public function updateUser($user, array $data)
    {
        if (is_int($user)) {
            $user = User::find($user);
        }

        $user->username = $data['username'] ?? null;
        $user->email = $data['email'] ?? null;

        if (data_get($data, 'password')) {
            $user->password = \Hash::make($data['password']);
        }

        return $user->save();
    }

    /**
     * upload avatar
     *
     * @param File $file
     * @param string $name
     * @param mixed $oldImg
     * @return string
     */
    protected function uploadAvatar($file, $name, $oldImg = null)
    {
        $ext = $file->getClientOriginalExtension();
        $imgName = pretty_string($name);
        $name = $imgName . rand(0, 10) . ".$ext";
        $path = "img";

        if (isset($oldImg) && $oldImg != config('timesheet.avatar')) {
            if (File::exists($oldImg)) {
                unlink($oldImg);
            }
        }

        try {
            $pathImg = $file->move($path, $name);
        } catch (Exception $e) {
            return config('timesheet.avatar');
        }

        return '/'. $pathImg;
    }

    /**
     * get info of user loggin
     *
     * @return void
     */
    public function getInfoUser()
    {
        $user = User::find(Auth::id());
        return $user;
    }

    /**
     * update info of user loggin
     *
     * @param array $data
     * @return void
     */
    public function updateProfile(array $data)
    {
        if (data_get($data, 'image')) {
            $username = Auth::user()->username;
            $avatar = Auth::user()->avatar;
            $resultUploadImg = $this->uploadAvatar($data["image"], $username, $avatar);
            $profile["avatar"] = $resultUploadImg ?? config('timesheet.avatar');
        }

        $profile["description"] = $data["description"] ?? null;

        if (data_get($data, 'password')) {
            $profile["password"] = \Hash::make($data["password"]);
        }

        $user = User::where("id", Auth::id())->update($profile);
        return $user;
    }

    /**
     * insight user
     *
     * @return void
     */
    public function insightUser()
    {
        $totalUser = User::withTrashed()->count();
        $totalUserInactive = User::onlyTrashed()->count();

        return [
            'totalUser' => $totalUser,
            'totalUserInactive' => $totalUserInactive
        ];
    }
}