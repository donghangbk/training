<?php
namespace App\Services;

use App\Models\User;
use App\Models\Role;
use App\Models\UserNotification;
use App\Models\Setting;
use App\Models\Timesheet;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use File;
use Carbon\Carbon;
use App\Services\Interfaces\UserServiceInterface;

class UserService implements UserServiceInterface
{

    public function listUser()
    {
        $listUser = User::orderBy('id', 'desc')->get();
        return $listUser;
    }

    public function formCreate()
    {
        $role = Role::all();
        $listUser = User::select("id", "username")->active()->role(User::ROLE_USER)->get();
        return [
            "role" => $role,
            "listUser" => $listUser
        ];
    }

    public function createUser($request)
    {
        $data = [
            'username' => $request['username'],
            'email' => $request['email'],
            'password' => config('timesheet.pass_default'),
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
            $resultUploadImg = $this->uploadImage($request["image"], $request["username"]);
            $data["avatar"] = $resultUploadImg ?? config('timesheet.avatar');
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

    public function formEdit($id)
    {
        $user = User::find($id);
        $role = Role::all();
        return [
            "user" => $user,
            "role" => $role,
            "id" => $id
        ];
    }

    public function updateUser($request, $id)
    {
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

    public function userProfile()
    {
        $id = Auth::id();
        $user = User::find($id);
        return $user;
    }

    public function updateUserProfile($request)
    {
        if (isset($request["image"])) {
            $username = Auth::user()->username;
            $avatar = Auth::user()->avatar;
            $resultUploadImg = $this->uploadImage($request["image"], $username, $avatar);
            $data["avatar"] = $resultUploadImg ?? config('timesheet.avatar');
        }

        $data["description"] = $request["description"];

        if (!empty($request["password"])) {
            $data["password"] = bcrypt($request["password"]);
        }

        $user = User::where("id", Auth::id())->update($data);
        return $user;
    }

    public function getSetting()
    {
        $setting = Setting::first();

        $data["start_time"] = Carbon::createFromFormat('Hi', $setting["start_time"])->format('g:i a');
        $data["end_time"] = Carbon::createFromFormat('Hi', $setting["end_time"])->format('g:i a');
        
        return $data;
    }

    public function updateSetting($request)
    {
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

    private function uploadImage($file, $name, $oldImg = null)
    {
        $ext = $file->getClientOriginalExtension();
        $imgName = $this->convertName($name);
        $name = $imgName . rand(0, 10) . ".$ext";
        $path = "img";

        if (isset($oldImg) && $oldImg != config('timesheet.avatar')) {
            if (File::exists($oldImg)) { // unlink or remove previous image from folder
                unlink($oldImg);
            }
        }

        try {
            $pathImg = $file->move($path, $name);
        } catch (Exception $e) {
            return null;
        }

        return '/'.$pathImg;
    }

    private function convertName($str)
    {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        $str = preg_replace("/(\“|\”|\‘|\’|\,|\!|\&|\;|\@|\#|\%|\~|\`|\=|\_|\'|\]|\[|\}|\{|\)|\(|\+|\^|\/)/", '-', $str);
        $str = preg_replace("/( )/", '-', $str);
        return $str;
    }
}