<?php

namespace App\Services\Admin\Interfaces;

interface SettingServiceInterface
{
    public function getSetting();
    public function updateSetting(array $data, $setting);
}