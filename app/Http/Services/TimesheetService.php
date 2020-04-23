<?php
namespace App\Services;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Timesheet;
use App\Models\TimesheetDetail;
use App\Models\UserNotification;
use App\Mail\SendMailable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Mail;

use App\Services\Interfaces\TimesheetServiceInterface;

class TimesheetService implements TimesheetServiceInterface {

    public function listTimesheet() {
        $timesheets = Timesheet::where("user_id", Auth::id())->orderBy("created_at", 'desc')->withCount('timesheetDetail as total')->get();

        return $timesheets;
    }

    public function createTimesheet(Request $request) {
        $data = [
            "user_id" => Auth::user()->id,
            "issue" => $request["issue"],
            "next_day" => $request["next_day"],
            "work_day" => $request["work_day"]
        ];
        $timesheet = Timesheet::create($data);

        $list = $request->all();
        $totalField = count($list);
        $maxId = ($totalField - 4) / 3;

        $arrDetail = [];
        for ($i = 1; $i <= $maxId; $i++) {
            $detail = [
            "timesheet_id" => $timesheet->id,
            "task_id" => $request["task$i"],
            "content" => $request["content$i"],
            "time" => $request["time$i"]
        ];
            $arrDetail[] = $detail;
        }

        $timesheetDetail = TimesheetDetail::insert($arrDetail);

        // send notification to leader and other users
        $this->sendEmail();
    }

    public function getDetail($id) {
        $timesheet = Timesheet::find($id);
        $detail = Timesheet::find($id)->timesheetDetail;
        return [
            "timesheet" => $timesheet,
            "detail" => $detail
        ];
    }

    public function member() {
        $idLeader = Auth::id();
        $timesheets = Timesheet::with("user")->whereHas("user", function($query) use ($idLeader) {
            $query->where("leader", $idLeader);
        })->withCount("timesheetDetail as total")->orderBy("timesheets.created_at", "desc")->get();
        
        return $timesheets;
    }

    public function updateTimesheet(Request $request, $id) {
        $data = [
            "issue" => $request["issue"],
            "next_day" => $request["next_day"],
            "work_day" => $request["work_day"],
            "status" => 0 // update status to wait admin to approve
        ];
        $timesheet = Timesheet::where("id", $id)->update($data);

        $list = $request->all();
        $totalField = count($list);
        $maxId = ($totalField - 4) / 3;

        // delete detail of timesheet_id after updating
        $rsDelete = TimesheetDetail::where("timesheet_id", $id)->delete();
        $arrDetail = [];
        for ($i = 1; $i <= $maxId; $i++) {
            $detail = [
            "timesheet_id" => $id,
            "task_id" => $request["task$i"],
            "content" => $request["content$i"],
            "time" => $request["time$i"]
        ];
            $arrDetail[] = $detail;
        }

        $timesheetDetail = TimesheetDetail::insert($arrDetail);

        // send Email
        $this->sendEmail();
    }

    public function search($request) {
        $from = $request->get("from");
        $to = $request->get("to");

        $conditions = [
            ["user_id", Auth::id()],
            ["work_day", ">=", $from],
            ["work_day", "<=", $to]
        ];
        $rsSearch = Timesheet::where($conditions)->withCount("timesheetDetail as total")->get();
        return $rsSearch;
    }
    private function sendEmail() {
        // Artisan::queue("notify:timesheet", ["params" => ["userId" => Auth::id(), "username" => Auth::user()->username], '--queue' => 'default']);
        $params = ["userId" => Auth::id(), "username" => Auth::user()->username];

        $listReceiverId = UserNotification::where("user_id", $params["userId"])->select("user_receive_id")->get();
        
        foreach ($listReceiverId as $receiverId) {
            $email = $receiverId->info->email;
            Mail::to($email)->queue(new SendMailable($params));
        }
    }
}