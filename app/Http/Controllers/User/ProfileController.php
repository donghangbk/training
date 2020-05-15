<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Session;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Services\Admin\Interfaces\UserServiceInterface;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    protected $userService;
    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }
    public function index()
    {
        $user = $this->userService->getInfoUser();
        return view("user.profile.index", compact('user'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $data = $request->only(['description', 'image', 'password']);
        $result = $this->userService->updateProfile($data);
        if (!$result) {
            return back()->withErrors(["msg" => "Something error while update. Please try again"]);
        }
        Session::flash("message", "Updated  success");
        return redirect()->route("profile");
    }
}
