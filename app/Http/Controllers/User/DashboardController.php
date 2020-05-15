<?php

namespace App\Http\Controllers\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\User\Interfaces\TimesheetServiceInterface;

class DashboardController extends Controller
{
    protected $timesheetService;

    public function __construct(TimesheetServiceInterface $timesheetService)
    {
        $this->timesheetService = $timesheetService;
    }

    public function index()
    {
        $data = $this->timesheetService->insightTimesheet();
        return view('user.dashboard.index', $data);
    }
}
