<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\UserNotification;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Auth;
use File;
use Carbon\Carbon;

class UsersController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $listUser = User::orderBy('id', 'desc')->get();;
        foreach ($listUser as &$item) {
            $item["created_at"] = date('Y-m-d', strtotime($item["created_at"]));
        }
        return view('users.index', compact("listUser"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $role = Role::all();
        $listUser = User::select("id", "username")->where("is_active", 1)->where("role_id", "<>", "1")->get(); // 1 is admin
        return view("users.create", ["role" => $role, "listUser" => $listUser]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'username' => 'required|string|alpha|max:31',
            'email' => 'required|string|email|max:255|unique:users',
            'role_id' => 'required|numeric',
            'description' => 'required|regex:/(([a-zA-z]+)(\d+)?$)/'
        ])->validate();

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
        // Session::flash('flash_message', 'User successfully added!');
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $role = Role::all();
        return view("users.edit", ["user" => $user, "role" => $role, "id" => $id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'username' => 'required|string|alpha|max:31',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id, 
            'role_id' => 'required|numeric',
            'password' => 'string|min:6|max:20|confirmed|nullable',
            'password_confirmation' => 'string|min:6|max:20|nullable'
        ])->validate();

        $data = [
            "username" => $request["username"],
            "email" => $request["email"],
            "role_id" => $request["role_id"]
        ];
        if (isset($request["password"])) {
            $data["password"] = bcrypt($request["password"]);
        }
        $suser = User::where("id", $id)->update($data);

        // Session::flash('flash_message', 'User successfully updated!');
        return redirect()->route('users.index');
    }

    public function editUser(Request $request, $id) {
        Validator::make($request->all(), [
            'username' => 'required|string|alpha|max:31',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id, 
            'role_id' => 'required|numeric',
            'password' => 'string|min:6|max:20|confirmed|nullable',
            'password_confirmation' => 'string|min:6|max:20|nullable'
        ])->validate();

        $data = [
            "username" => $request["username"],
            "email" => $request["email"],
            "role_id" => $request["role_id"]
        ];
        if (isset($request["password"])) {
            $data["password"] = bcrypt($request["password"]);
        }
        $suser = User::where("id", $id)->update($data);

        // Session::flash('flash_message', 'User successfully updated!');
        return redirect()->route('users.index');
    }

    public function profile(Request $request) {
        $id = Auth::id();
        $user = User::find($id);
        if ($request->isMethod('get')) {
            return view("users.profile", compact("user"));
        }

        if ($request->isMethod('post'))  {
            Validator::make($request->all(), [
                'description' => 'required|regex:/(([a-zA-z]+)(\d+)?$)/',
                'current_password' => ['nullable',new MatchOldPassword],
                'password' => 'nullable|string|min:6|max:20|confirmed|different:current_password',
                'password_confirmation' => 'string|min:6|max:20|nullable'
            ])->validate();

            if (isset($request["image"])) {
                $username = $user->username;
                $avatar = $user->avatar;
                $result = $this->__uploadImage($request["image"], $username, $avatar);
                $data["avatar"] = $result != false ? $result : config('timesheet.avatar');
            }

            $data["description"] = $request["description"];

            if (!empty($request["password"])) {
                $data["password"] = bcrypt($request["password"]);
            }

            $user = User::where("id", $id)->update($data);
            return redirect()->route("profile");
        }
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

    public function setting(Request $request) {
        if ($request->isMethod('get')) {
            $setting = Setting::take(1)->get();
        
            $data["start_time"] = Carbon::createFromFormat('Hi', $setting[0]["start_time"])->format('g:i a');
            $data["end_time"] = Carbon::createFromFormat('Hi', $setting[0]["end_time"])->format('g:i a');
            return view("users.setting", compact("data"));
        } else {
            $start24 = Carbon::createFromFormat('g:i a', $request["start_time"])->format('Hi');
            $end24 = Carbon::createFromFormat('g:i a', $request["end_time"])->format('Hi');
            Setting::where('id',1)->update([
                "start_time" => $start24,
                "end_time" => $end24
            ]);
            return redirect()->route("setting");
        }
    }
}
