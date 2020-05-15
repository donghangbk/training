<?php
namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Response;
use App\Models\Timesheet;
use App\Http\Controllers\Controller;

class AjaxController extends Controller
{
    public function approve(Request $request)
    {
        if ($request->input('id')) {
            $updateData = [
                "status" => 1
            ];
            $rsUpdate = Timesheet::find($request->input('id'))->update($updateData);
            if (!$rsUpdate) {
                return Response::json(['error' => 'save error'], 400);
            }
            return Response::json(['error' => ''], 200);
        } else {
            return Response::json(['error' => 'id not found'], 404);
        }
    }
}