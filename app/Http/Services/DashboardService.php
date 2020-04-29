<?php
namespace App\Services;

use App\Models\User;
use App\Models\Timesheet;
use App\Models\Setting;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Services\Interfaces\DashboardServiceInterface;
class DashboardService implements DashboardServiceInterface
{
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
        $oneAgo = date('Y/m', strtotime('-1 year'));

        $totalLateTimesheet = Timesheet::where("user_id", Auth::id())
                                ->where(DB::raw('DATE_FORMAT(work_day, "%Y/%m")'),  date("Y/m"))          // limit 1 month
                                ->where(function($query) use ($orWhere) {
                                    $query->whereDate("created_at", "<>", DB::raw('DATE(work_day)')) // create timesheet the next day
                                          ->orwhere("created_at", ">", DB::raw($orWhere)); // after end_time setting
                                })
                                ->count();
           
        $createByMonth = Timesheet::where("user_id", Auth::id())
                                ->where(DB::raw('DATE_FORMAT(work_day, "%Y/%m")'), ">=",  $oneAgo) // limit 1 year
                                ->select(DB::raw('DATE_FORMAT(work_day, "%m/%Y") AS datee'), DB::raw('COUNT(id) total'))
                                ->groupBy(DB::raw('DATE_FORMAT(work_day, "%m/%Y")'))
                                ->orderBy("datee")
                                ->get();

        $delayByMonth = Timesheet::where("user_id", Auth::id())
                                ->where(DB::raw('DATE_FORMAT(work_day, "%Y/%m")'), ">=",  $oneAgo)          // limit 1 year
                                ->where(function($query) use ($orWhere) {
                                    $query->whereDate("created_at", "<>", DB::raw('DATE(work_day)')) // create timesheet the next day
                                          ->orwhere("created_at", ">", DB::raw($orWhere)); // after end_time setting
                                })
                                ->select(DB::raw('DATE_FORMAT(work_day, "%m/%Y") AS datee'), DB::raw('COUNT(id) total'))
                                ->groupBy(DB::raw('DATE_FORMAT(work_day, "%m/%Y")'))
                                ->orderBy("datee")
                                ->get();

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
}