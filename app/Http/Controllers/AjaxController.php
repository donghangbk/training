<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Timesheet;

class AjaxController extends Controller
{

    public function deleteUser(Request $request)
    {
        if (isset($request["id"])) {
            $rsUpdate = User::find($request["id"])->delete();
            if (!$rsUpdate) {
                return Response::json(['error' => 'save error'], 400);
            }

            return Response::json(['error' => ''], 200);
        } else {
            return Response::json(['error' => 'id not found'], 404);
        }
    }

    public function approve(Request $request)
    {
        if (isset($request["id"])) {
            $updateData = [
                "status" => 1
            ];
            $rsUpdate = Timesheet::find($request["id"])->update($updateData);
            if (!$rsUpdate) {
                return Response::json(['error' => 'save error'], 400);
            }
            return Response::json(['error' => ''], 200);
        } else {
            return Response::json(['error' => 'id not found'], 404);
        }
    }
}