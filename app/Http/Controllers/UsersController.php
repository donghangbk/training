<?php
namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\UpdateProfileRequest;

use Illuminate\Http\Request;
use App\Services\Interfaces\UserServiceInterface;

class UsersController extends Controller
{

    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listUser = $this->userService->listUser();
        return view('users.index', compact("listUser"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = $this->userService->formCreate();
        return view("users.create", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        $user = $this->userService->createUser($request);
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
        $data = $this->userService->formEdit($id);
        return view("users.edit", $data);
    }

    public function update(EditUserRequest $request, $id)
    {
        $rsUpdate = $this->userService->updateUser($request, $id);

        // Session::flash('flash_message', 'User successfully updated!');
        return redirect()->route('users.index');
    }

    public function profile()
    {
        $user = $this->userService->userProfile();
        return view("users.profile", compact("user"));
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $this->userService->updateUserProfile($request);
        return redirect()->route("profile");
    }

    public function setting()
    {
        $data = $this->userService->getSetting();
        return view("users.setting", compact("data")); 
    }

    public function updateSetting(Request $request)
    {
        $rsUpdate = $this->userService->updateSetting($request);
        return redirect()->route("setting");
    }
}
