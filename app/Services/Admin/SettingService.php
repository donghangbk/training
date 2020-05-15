<?php
namespace App\Services\Admin;

use App\Models\Setting;
use Carbon\Carbon;
use App\Services\Admin\Interfaces\SettingServiceInterface;

class SettingService implements SettingServiceInterface
{
    /**
     * get setting
     *
     * @return void
     */
    public function getSetting()
    {
        $setting = Setting::first();

        $data["start_time"] = Carbon::createFromFormat('Hi', $setting["start_time"])->format('g:i a');
        $data["end_time"] = Carbon::createFromFormat('Hi', $setting["end_time"])->format('g:i a');
        $data["id"] = $setting["id"];
        
        return $data;
    }

    /**
     * Update setting
     *
     * @param array $data
     * @param [Setting] $setting
     * @return void
     */
    public function updateSetting(array $data, $setting)
    {
        $start24 = Carbon::createFromFormat('g:i a', $data["start_time"])->format('Hi');
        $end24 = Carbon::createFromFormat('g:i a', $data["end_time"])->format('Hi');
        
        $setting->start_time = $start24;
        $setting->end_time = $end24;
        
        return $setting->save();
    }
}