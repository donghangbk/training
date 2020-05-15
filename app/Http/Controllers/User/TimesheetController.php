<?php

namespace App\Http\Controllers\User;

use App\Models\Timesheet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\User\SearchTimesheetRequest;
use App\Services\User\Interfaces\TimesheetServiceInterface;

class TimesheetsController extends Controller
{
    protected $timesheetService;

    public function __construct(TimesheetServiceInterface $timesheetService)
    {
        $this->timesheetService = $timesheetService;
    }

    public function index()
    {
        $timesheets = $this->timesheetService->listTimesheet();

        return view('user.timesheets.index', compact('timesheets'));
    }

    public function create()
    {
        return view('user.timesheets.create');
    }

    public function store(Request $timesheet)
    {
        $data = $timesheet->only(['issue', 'next_day', 'work_day', 'task']);
        $resultCreate = $this->timesheetService->createTimesheet($data);
        if ($resultCreate) {
            return redirect()->route('timesheets.index');
        } else {
            return back()->withErrors(['msg' => 'Something error while update']);
        }
    }

    public function show(Timesheet $timesheet)
    {
        $data = [
            'timesheet' => $timesheet,
            'task' => $timesheet->tasks
        ];

        return view("user.timesheets.show", $data);
    }

    public function edit(Timesheet $timesheet)
    {
        $data = [
            'timesheet' => $timesheet,
            'task' => $timesheet->tasks
        ];
        
        return view('user.timesheets.edit', $data);
    }

    public function update(Request $request, Timesheet $timesheet)
    {
        $data = $request->only(['issue', 'next_day', 'work_day', 'task']);
        $result = $this->timesheetService->updateTimesheet($timesheet, $data);
        if (!$result) {
            return back()->withErrors(["msg" => "Something error while update. Please try again"]);
        }
        return redirect()->route("timesheets.index");
    }

    public function search(SearchTimesheetRequest $search)
    {
        $resultSearch = $this->timesheetService->searchTimesheet($search);

        return view("user.timesheets.index")->with("timesheets", $resultSearch);
    }

    public function member()
    {
        $timesheets = $this->timesheetService->getTimesheetsOfMembers();
        return view('user.timesheets.member', compact('timesheets'));
    }
}
