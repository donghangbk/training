<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Timesheet;

class AjaxController extends Controller {

    public function deleteUser(Request $request) {
        if (isset($request["id"])) {
            $updateData = [
                "is_active" => 0,
                "deleted" => date("Y-m-d H:i:s")
            ];

            $rsUpdate = User::where("id", $request["id"])->update($updateData);
            if (!$rsUpdate) {
                return json_encode(["res" => false]);
            }

            return json_encode(["res" => true]);
        }
    }

    public function approve(Request $request) {
        if (isset($request["id"])) {
            $updateData = [
                "status" => 1
            ];
            $rsUpdate = Timesheet::where("id", $request["id"])->update($updateData);
            if (!$rsUpdate) {
                return json_encode(["res" => false]);
            }
            return json_encode(["res" => true]);
        }
    }
}