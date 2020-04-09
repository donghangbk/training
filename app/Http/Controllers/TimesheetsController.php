<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Timesheet;
use App\TimesheetDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TimesheetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $timesheets = Timesheet::where("user_id", Auth::id())->orderBy("created_at", 'desc')->get();
        
        foreach ($timesheets as &$item) {
            $countTask = TimesheetDetail::all()->where("timesheet_id", $item["id"])->count();
            $item["total"] = $countTask;
        }
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
        $data = [
            "user_id" => Auth::user()->id,
            "issue" => $request["issue"],
            "next_day" => $request["next_day"],
            "work_day" => $request["work_day"]
        ];

        $timesheet = Timesheet::create($data);
        $detail = [
            "timesheet_id" => $timesheet->id,
            "task_id" => $request["task1"],
            "content" => $request["content1"],
            "time" => $request["time1"]
        ];
        $timesheetDetail = TimesheetDetail::create($detail);

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
        $timesheet = Timesheet::find($id);
        Log::info($timesheet);
        $detail = Timesheet::find($id)->timesheetDetail;
        return view("timesheets.show", ['timesheet' => $timesheet, "detail" => $detail]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
}
