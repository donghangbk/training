<?php
namespace App\Http\Traits;

use App\Models\User;
use App\Models\Role;
use App\Models\UserNotification;
use App\Models\Setting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use File;
use Carbon\Carbon;

trait UserService {

    public function listUser() {
        $listUser = User::orderBy('id', 'desc')->get();;
        foreach ($listUser as &$item) {
            $item["created_at"] = date('Y-m-d', strtotime($item["created_at"]));
        }
        return $listUser;
    }

    public function formCreate() {
        $role = Role::all();
        $listUser = User::select("id", "username")->where("is_active", 1)->where("role_id", "<>", "1")->get(); // 1 is admin
        return [
            "role" => $role,
            "listUser" => $listUser
        ];
    }

    public function createUser(Request $request) {
        $data = [
            'username' => $request['username'],
            'email' => $request['email'],
            'password' => bcrypt("1234567"),
            'role_id' => $request['role_id'],
            'description' => $request["description"]
        ];

        // mark to have leader
        $haveLeader = false;
        if (!empty($request["leader"])) {
            $data['leader'] = $request['leader'];  
            $haveLeader = true;
        }

        if (isset($request["image"])) {
            $resultUploadImg = $this->__uploadImage($request["image"], $request["username"]);
            $data["avatar"] = $resultUploadImg != false ? $resultUploadImg : config('timesheet.avatar');
        } else {
            $data["avatar"] = config('timesheet.avatar');
        }
        $newUser = User::create($data);

        $arrNoti = [];
        if (!empty($request["listUser"])) {
            foreach ($request['listUser'] as $item) {
                if ($haveLeader && $request["leader"] == $item) {
                    continue;
                }
                $arr = [
                    'user_id' => $newUser->id,
                    'user_receive_id' => $item
                ];
                $arrNoti[] = $arr;
            }
        }

        // add leader to notification
        if ($haveLeader) {
            $arrNoti[] = [
                'user_id' => $newUser->id,
                'user_receive_id' => $request["leader"]
            ];
        }

        // add notifincations
        if (count($arrNoti) > 0) {
            $noti = UserNotification::insert($arrNoti);
        }
        return true;
    }

    public function formEdit($id) {
        $user = User::find($id);
        $role = Role::all();
        return [
            "user" => $user,
            "role" => $role,
            "id" => $id
        ];
    }

    public function updateUser($request, $id) {
        $data = [
            "username" => $request["username"],
            "email" => $request["email"],
            "role_id" => $request["role_id"]
        ];
        if (isset($request["password"])) {
            $data["password"] = bcrypt($request["password"]);
        }
        $user = User::where("id", $id)->update($data);
        return $user;
    }

    public function userProfile() {
        $id = Auth::id();
        $user = User::find($id);
        return $user;
    }

    public function updateUserProfile($request) {
        if (isset($request["image"])) {
            $username = Auth::user()->username;
            $avatar = Auth::user()->avatar;
            $result = $this->__uploadImage($request["image"], $username, $avatar);
            $data["avatar"] = $result != false ? $result : config('timesheet.avatar');
        }

        $data["description"] = $request["description"];

        if (!empty($request["password"])) {
            $data["password"] = bcrypt($request["password"]);
        }

        $user = User::where("id", Auth::id())->update($data);
        return $user;
    }

    public function getSetting() {
        $setting = Setting::take(1)->get();

        $data["start_time"] = Carbon::createFromFormat('Hi', $setting[0]["start_time"])->format('g:i a');
        $data["end_time"] = Carbon::createFromFormat('Hi', $setting[0]["end_time"])->format('g:i a');
        return $data;
    }

    public function updateSetting($request) {
        $start24 = Carbon::createFromFormat('g:i a', $request["start_time"])->format('Hi');
        $end24 = Carbon::createFromFormat('g:i a', $request["end_time"])->format('Hi');
        $rsUpdate = Setting::where('id',1)->update([
            "start_time" => $start24,
            "end_time" => $end24
        ]);
        if (!$rsUpdate) {
            return false;
        }
        return true;
    }

    private function __uploadImage($file, $name, $oldImg = null) {
        $ext = $file->getClientOriginalExtension();
        $imgName = $this->convert_name($name);
        $name = $imgName . rand(0, 10) . ".$ext";
        $path = "img";

        if (isset($oldImg) && $oldImg != config('timesheet.avatar')) {
            if (File::exists($oldImg)) { // unlink or remove previous image from folder
                unlink($oldImg);
            }
        }
        try {
            $pathImg = $file->move($path, $name);
        } catch (Exepction $e) {
            return false;
        }
        return '/'.$pathImg;
}
}