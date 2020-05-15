<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\Interfaces\UserServiceInterface;

class DashboardController extends Controller
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
        $data = $this->userService->insightUser();
        return view("admin.dashboard.index", $data);
    }
    
    public function logout()
    {
        \Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
