<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\User;

class AjaxController extends Controller {

    public function deleteUser(Request $request) {
        if ($request->isMethod('post')) {
            if (isset($request["id"])) {
                $updateData = [
                    "is_active" => 0,
                    "deleted" => date("Y-m-d H:i:s")
                ];
                try {
                    User::where("id", $request["id"])->update($updateData);
                } catch (\Throwable $th) {
                    return json_encode(["res" => false]);
                }
                return json_encode(["res" => true]);
            }
        }
    }
}