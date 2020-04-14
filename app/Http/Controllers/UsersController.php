<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidationCreateUserRequest;
use App\Http\Requests\ValidationEditUserRequest;
use App\Http\Requests\ValidationUpdateProfileRequest;

use App\Http\Traits\UserService;

class UsersController extends Controller {
    use UserService;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $listUser = $this->listUser();
        return view('users.index', compact("listUser"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $data = $this->formCreate();
        return view("users.create", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidationCreateUserRequest $request)
    {
        $user = $this->createUser($request);
        // Session::flash('flash_message', 'User successfully added!');
        return redirect()->route('users.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->formEdit($id);
        return view("users.edit", $data);
    }

    public function editUser(ValidationEditUserRequest $request, $id) {
        $rsUpdate = $this->updateUser($request, $id);

        // Session::flash('flash_message', 'User successfully updated!');
        return redirect()->route('users.index');
    }

    public function profile() {
        $user = $this->userProfile();
        return view("users.profile", compact("user"));
    }

    public function updateProfile(ValidationUpdateProfileRequest $request) {
        $user = $this->updateUserProfile($request);
        return redirect()->route("profile");
    }

    public function setting(Request $request) {
        if ($request->isMethod('get')) {
            $data = $this->getSetting();
            return view("users.setting", compact("data"));
        } else {
            $rsUpdate = $this->updateSetting($request);
            return redirect()->route("setting");
        }
    }
}
