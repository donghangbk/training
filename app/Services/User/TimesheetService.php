<?php
namespace App\Services\User;

use DB;
use Mail;
use App\Models\User;
use App\Models\Setting;
use App\Models\Timesheet;
use App\Mail\SendMailable;
use Illuminate\Http\Request;
use App\Jobs\NotifyTimesheet;
use App\Models\TimesheetDetail;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use App\Services\User\Interfaces\TimesheetServiceInterface;

class TimesheetService implements TimesheetServiceInterface
{
    /**
     * get list user's timesheet
     *
     * @return array
     */
    public function listTimesheet()
    {
        $timesheets = Timesheet::where("user_id", Auth::id())->orderBy("created_at", 'desc')->withCount('tasks as total')->get();

        return $timesheets;
    }

    /**
     * create timesheet
     *
     * @param array $timesheet
     * @return boolean
     */
    public function createTimesheet(array $timesheet)
    {
        $data = [
            "user_id" => Auth::id(),
            "issue" => $timesheet["issue"],
            "next_day" => $timesheet["next_day"],
            "work_day" => $timesheet["work_day"]
        ];
        $newTimesheet = Timesheet::create($data);

        if (data_get($timesheet, 'task')) {
            $tasks = [];
            foreach ($timesheet['task'] as $item) {
                $tasks[] = [
                    "task_id" => $item["taskId"],
                    "content" => $item["content"],
                    "time" => $item["time"]
                ];
            }

            $newTimesheet->tasks()->createMany($tasks);
        }

        // send Email
        $this->sendEmail();

        return true;
    }

    /**
     * update timesheet
     *
     * @param [Timesheet] $timesheet
     * @param array $data
     * @return boolean
     */
    public function updateTimesheet($timesheet, array $data)
    {
        $timesheet->issue = $data["issue"] ?? null;
        $timesheet->next_day = $data["next_day"] ?? null;
        $timesheet->work_day = $data["work_day"] ?? $timesheet->work_day;
        $timesheet->status = Timesheet::TIMESHEET_WAITING;
        
        $resultUpdate = $timesheet->save();
        if (!$resultUpdate) {
            return false;
        }

        // delete detail of timesheet_id after updating
        $rsDelete = $timesheet->tasks()->delete();
        
        if (data_get($data, 'task')) {
            $tasks = [];
            foreach ($data["task"] as $item) {
                $tasks[] = [
                    "task_id" => $item["taskId"] ?? null,
                    "content" => $item["content"] ?? null,
                    "time" => $item["time"] ?? null
                ];
            }
    
            $timesheetDetail = $timesheet->tasks()->createMany($tasks);
            if (!$timesheetDetail) {
                return false;
            }
        }

        // send Email
        $this->sendEmail();

        return true;
    }

    /**
     * search timesheet follow by date
     *
     * @param [request] $search
     * @return array
     */
    public function searchTimesheet($search)
    {
        $from = $search->get("from");
        $to = $search->get("to");

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

    /**
     * get timesheet of member
     *
     * @return array
     */
    public function getTimesheetsOfMembers()
    {
        $timesheets = Timesheet::join("users", "user_id", "users.id")
                                ->join("timesheet_detail", "timesheet_id", "timesheets.id")
                                ->where("users.leader", Auth::id())
                                ->orderBy("timesheets.created_at", "desc")
                                ->select("timesheets.*", "users.username", DB::raw('count(timesheets.id) as total'))
                                ->groupBy("timesheets.id")
                                ->paginate(config('timesheet.paginate'));

        return $timesheets;
    }
    
    /**
     * insight timesheet
     *
     * @return array
     */
    public function insightTimesheet()
    {
        // total user
        $totalUser = User::role(User::ROLE_USER)->count();

        // total timesheet follow current month 
        $totalTsMonth = Timesheet::where('user_id', Auth::id())
                                ->whereYear('work_day', date('Y'))
                                ->whereMonth('work_day', date('m'))
                                ->count();

        // total member of user who login
        $totalMemberOfUser = User::where("leader", Auth::id())->count();

        // total timesheet late in month
        $setting = Setting::first();
        $orWhere = 'CONCAT(DATE(CREATED_AT)," ",'. substr($setting["end_time"], 0, 2).', ":", '. substr($setting["end_time"], 2, 3).' , ":00")';
        $oneYearAgo = date('Y/m', strtotime('-1 year'));

        $totalLateTimesheet = $this->getTimesheetLateOfMonth($orWhere);
           
        $createByMonth = $this->getTimesheetByMonth($oneYearAgo);

        $delayByMonth = $this->getTimesheetLateByMonth($oneYearAgo, $orWhere);

        $time = [];
        $formatCreateByMonth = [];
        $formatDelayByMonth = [];

        // generate 12 month
        for($i = 11; $i >= 0; $i--) {
            $datee = date("m/Y", strtotime("-$i months"));
            $time[] = $datee;
            $formatCreateByMonth[$datee] = 0;
            $formatDelayByMonth[$datee] = 0;
        }

        foreach ($createByMonth as $value) {
            if(isset($formatCreateByMonth[$value["datee"]])) {
                $formatCreateByMonth[$value["datee"]] = $value["total"];
            }
        }

        foreach ($delayByMonth as $value) {
            if(isset($formatDelayByMonth[$value["datee"]])) {
                $formatDelayByMonth[$value["datee"]] = $value["total"];
            }
        }
        
        $formatCreateByMonth = array_values($formatCreateByMonth);
        $formatDelayByMonth = array_values($formatDelayByMonth);
        
        return [
            "totalUser" => $totalUser,
            "totalTsMonth" => $totalTsMonth,
            "totalMemberOfUser" => $totalMemberOfUser,
            "totalLateTimesheet" => $totalLateTimesheet,
            "time" => json_encode($time),
            "createByMonth" => json_encode($formatCreateByMonth),
            "delayByMonth" =>  json_encode($formatDelayByMonth)
        ];
    }
    
    /**
     * insight timesheets which was late
     *
     * @param [string] $condition
     * @return int
     */
    private function getTimesheetLateOfMonth($condition)
    {
        $totalLateTimesheet = Timesheet::where("user_id", Auth::id())
                                ->where(DB::raw('DATE_FORMAT(work_day, "%Y/%m")'),  date("Y/m"))          // limit 1 month
                                ->where(function($query) use ($condition) {
                                    $query->whereDate("created_at", "<>", DB::raw('DATE(work_day)')) // create timesheet the next day
                                          ->orwhere("created_at", ">", DB::raw($condition)); // after end_time setting
                                })
                                ->count();
        return $totalLateTimesheet;
    }

    /**
     * insight timesheet by month
     *
     * @param [string] $oneYearAgo
     * @return array
     */
    private function getTimesheetByMonth($oneYearAgo)
    {
        $list = Timesheet::where("user_id", Auth::id())
                                ->where(DB::raw('DATE_FORMAT(work_day, "%Y/%m")'), ">=",  $oneYearAgo) // limit 1 year
                                ->select(DB::raw('DATE_FORMAT(work_day, "%m/%Y") AS datee'), DB::raw('COUNT(id) total'))
                                ->groupBy(DB::raw('DATE_FORMAT(work_day, "%m/%Y")'))
                                ->orderBy("datee")
                                ->get();
        return $list;
    }

    /**
     * insight timesheet late by month
     *
     * @param [string] $oneYearAgo
     * @param [string] $condition
     * @return array
     */
    private function getTimesheetLateByMonth($oneYearAgo, $condition)
    {
        $list = Timesheet::where("user_id", Auth::id())
                                ->where(DB::raw('DATE_FORMAT(work_day, "%Y/%m")'), ">=",  $oneYearAgo)          // limit 1 year
                                ->where(function($query) use ($condition) {
                                    $query->whereDate("created_at", "<>", DB::raw('DATE(work_day)')) // create timesheet the next day
                                          ->orwhere("created_at", ">", DB::raw($condition)); // after end_time setting
                                })
                                ->select(DB::raw('DATE_FORMAT(work_day, "%m/%Y") AS datee'), DB::raw('COUNT(id) total'))
                                ->groupBy(DB::raw('DATE_FORMAT(work_day, "%m/%Y")'))
                                ->orderBy("datee")
                                ->get();
        return $list;
    }

    /**
     * send email after creating or updating timesheets
     *
     * @return void
     */
    private function sendEmail()
    {
        $user = ["userId" => Auth::id(), "username" => Auth::user()->username];

        NotifyTimesheet::dispatch($user);
    }
}