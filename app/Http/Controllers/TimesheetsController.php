<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SearchRequest;
use Illuminate\Support\Facades\Log;

use App\Services\Interfaces\TimesheetServiceInterface;

class TimesheetsController extends Controller
{
    protected $timesheetService;

    public function __construct(TimesheetServiceInterface $timesheetService) {
        $this->timesheetService = $timesheetService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $timesheets = $this->timesheetService->listTimesheet();
        
        return view("timesheets.index", compact('timesheets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("timesheets.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->timesheetService->createTimesheet($request);

        return redirect()->route("timesheets.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->timesheetService->getDetail($id);

        return view("timesheets.show", ['timesheet' => $data["timesheet"], "detail" => $data["detail"]]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->timesheetService->getDetail($id);

        return view("timesheets.edit", ["timesheet" => $data["timesheet"], 'listTask' => $data["detail"]]);
    }

    public function update(Request $request, $id)
    {
        $this->timesheetService->updateTimesheet($request, $id);
        return redirect()->route("timesheets.index");
    }

    public function member() {
        $timesheets = $this->timesheetService->member();
        return view("timesheets.member", compact("timesheets"));
    }

    public function editTimesheet(Request $request, $id) {
        $this->timesheetService->updateTimesheet($request, $id);
        return redirect()->route("timesheets.index");
    }

    public function search(SearchRequest $request) {
        $resultSearch = $this->timesheetService->search($request);
        
        return view("timesheets.index")->with("timesheets", $resultSearch);
    }
}
