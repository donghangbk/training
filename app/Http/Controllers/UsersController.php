<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\UserNotification;
use App\Setting;
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

        if (isset($request["leader"])) {
            $data['leader'] = $request['leader'];
        }

        if (isset($request["image"])) {
            $file = $request["image"];
            $ext = $file->getClientOriginalExtension();
            $imgName = $this->convert_name($request["username"]);
            $name = $imgName . rand(0, 10) . ".$ext";
            $path = "img";

            try {
                $pathImg = $file->move($path, $name);
                $data["avatar"] = '/'.$pathImg;
            } catch (Exepction $e) {
    
            }
        } else {
            $data["avatar"] = '/img/avatar.png';
        }
        $newUser = User::create($data);

        if (isset($request["listUser"])) {
            $arrNoti = [];
            foreach ($request['listUser'] as $item) {
                $arr = [
                    'user_id' => $newUser->id,
                    'user_receive_id' => $item
                ];
                $arrNoti[] = $arr;
            }
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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

        if ($request->isMethod('post'))  {Log::info($request);
            Validator::make($request->all(), [
                'description' => 'required|regex:/(([a-zA-z]+)(\d+)?$)/',
                'current_password' => ['nullable',new MatchOldPassword],
                'password' => 'nullable|string|min:6|max:20|confirmed|different:current_password',
                'password_confirmation' => 'string|min:6|max:20|nullable'
            ])->validate();

            if (isset($request["image"])) {
                $username = $user->username;
                $avatar = $user->avatar;
                $result = $this->uploadImage($request["image"], $username, $avatar);
                $data["avatar"] = $result != false ? $result : '/img/avatar.png';
            }

            $data["description"] = $request["description"];

            if (!empty($request["password"])) {
                $data["password"] = bcrypt($request["password"]);
            }

            $user = User::where("id", $id)->update($data);
            return redirect()->route("profile");
        }
    }

    public function uploadImage($file, $name, $oldImg = null) {
            $ext = $file->getClientOriginalExtension();
            $imgName = $this->convert_name($name);
            $name = $imgName . rand(0, 10) . ".$ext";
            $path = "img";

            if (isset($oldImg) && $oldImg != '/img/avatar') {
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
