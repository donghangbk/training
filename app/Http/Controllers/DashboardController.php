<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Services\Interfaces\DashboardServiceInterface;
class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardServiceInterface $dashboardService) {
        $this->dashboardService = $dashboardService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $data = $this->dashboardService->insightTimesheet();
        return view("dashboard.index", $data);
    }
    
    public function logout() {
        Auth::logout();
        return redirect('/login');
    }
}
