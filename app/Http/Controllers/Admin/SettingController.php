<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Services\Admin\Interfaces\SettingServiceInterface;

class SettingController extends Controller
{
    protected $settingService;

    public function __construct(SettingServiceInterface $settingService)
    {
        $this->settingService = $settingService;
    }
    public function index()
    {
        $data = $this->settingService->getSetting();
        return view("admin.setting.index", compact("data"));
    }

    public function update(Request $request, Setting $setting)
    {
        $data = $request->only(['start_time', 'end_time']);
        $result = $this->settingService->updateSetting($data, $setting);
        if ($result) {
            Session::flash('message', 'Updated success');
            return redirect()->route("admin.setting.index");
        }
        return back()->withErrors(['msg' => 'Something error while update']);
        
    }
}
