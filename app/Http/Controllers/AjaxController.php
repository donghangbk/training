<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Response;
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
                return Response::json(['error' => 'save error'], 400);
            }

            return Response::json(['error' => ''], 200);
        }
    }

    public function approve(Request $request) {
        if (isset($request["id"])) {
            $updateData = [
                "status" => 1
            ];
            $rsUpdate = Timesheet::where("id", $request["id"])->update($updateData);
            if (!$rsUpdate) {
                return Response::json(['error' => 'save error'], 400);
            }
            return Response::json(['error' => ''], 200);
        }
    }
}