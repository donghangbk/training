<?php
namespace App\Services;

use Illuminate\Http\Request;
use App\Jobs\NotifyTimesheet;
use App\Models\User;
use App\Models\Timesheet;
use App\Models\TimesheetDetail;
use App\Models\UserNotification;
use App\Mail\SendMailable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Mail;
use DB;
use App\Services\Interfaces\TimesheetServiceInterface;

class TimesheetService implements TimesheetServiceInterface
{

    public function listTimesheet()
    {
        $timesheets = Timesheet::where("user_id", Auth::id())->orderBy("created_at", 'desc')->withCount('timesheetDetail as total')->get();

        return $timesheets;
    }

    public function createTimesheet($request)
    {
        $data = [
            "user_id" => Auth::id(),
            "issue" => $request["issue"],
            "next_day" => $request["next_day"],
            "work_day" => $request["work_day"]
        ];
        
        $timesheet = Timesheet::create($data);

        // Đoạn này sử dụng relationship sẽ ổn hơn nhé.

        https://laravel.com/docs/7.x/eloquent-relationships#the-save-method
        
        if (isset($request->task)) {
            $arrDetail = [];
            foreach ($request->task as $item) {
                $detail = [
                    "timesheet_id" => $timesheet->id,
                    "task_id" => $item["taskId"],
                    "content" => $item["content"],
                    "time" => $item["time"]
                ];
                $arrDetail[] = $detail;
            }

            $timesheetDetail = TimesheetDetail::insert($arrDetail);
        }

        // send notification to leader and other users
        // $this->sendEmail();
    }

    public function getDetail($id)
    {
        $timesheet = Timesheet::find($id);
        $detail = $timesheet->timesheetDetail;
        return [
            "timesheet" => $timesheet,
            "detail" => $detail
        ];
    }

    public function getTimesheetsOfMembers()
    {
        $idLeader = Auth::id();
        $timesheets = Timesheet::join("users", "user_id", "users.id")
                                ->join("timesheet_detail", "timesheet_id", "timesheets.id")
                                ->where("users.leader", $idLeader)
                                ->orderBy("timesheets.created_at", "desc")
                                ->select("timesheets.*", "users.username", DB::raw('count(timesheets.id) as total'))
                                ->groupBy("timesheets.id")
                                ->get();

        return $timesheets;
    }

    public function updateTimesheet($request, $id)
    {
        $data = [
            "issue" => $request["issue"],
            "next_day" => $request["next_day"],
            "work_day" => $request["work_day"],
            "status" => 0 // update status to wait admin to approve
        ];
        $timesheet = Timesheet::where("id", $id)->update($data);

        // delete detail of timesheet_id after updating
        $rsDelete = TimesheetDetail::where("timesheet_id", $id)->delete();
        
        if (isset($request->task)) {
            $arrDetail = [];
            foreach ($listTask as $item) {
                $detail = [
                    "timesheet_id" => $id,
                    "task_id" => $item["taskId"],
                    "content" => $item["content"],
                    "time" => $item["time"]
                ];
                $arrDetail[] = $detail;
            }
    
            $timesheetDetail = TimesheetDetail::insert($arrDetail);
        }

        // send Email
        // $this->sendEmail();
    }

    public function searchTimesheet($request)
    {
        $from = $request->get("from");
        $to = $request->get("to");

        $conditions = [
            ["user_id", Auth::id()],
            ["work_day", ">=", $from],
            ["work_day", "<=", $to]
        ];
        $rsSearch = Timesheet::where($conditions)
                                ->select("timesheets.*", DB::raw('count(timesheets.id) as total'))
                                ->groupBy("timesheets.id")
                                ->orderBy("timesheets.created_at", "desc")
                                ->get();
        return $rsSearch;
    }

    private function sendEmail()
    {
        // C1
        // Artisan::queue("notify:timesheet", ["params" => ["userId" => Auth::id(), "username" => Auth::user()->username], '--queue' => 'remide]);
        $user = ["userId" => Auth::id(), "username" => Auth::user()->username];

        NotifyTimesheet::dispatch($user);

        //C3
        // $listReceiverId = UserNotification::where("user_id", $params["userId"])->select("user_receive_id")->get();
        
        // foreach ($listReceiverId as $receiverId) {
        //     $email = $receiverId->info->email;
        //     Mail::to($email)->queue(new SendMailable($params));
        // }
    }
}