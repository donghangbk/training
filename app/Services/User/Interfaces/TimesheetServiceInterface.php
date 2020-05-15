<?php

namespace App\Services\User\Interfaces;

interface TimesheetServiceInterface
{
    public function listTimesheet();
    public function createTimesheet(array $timesheet);
    public function updateTimesheet($timesheet, array $data);
    public function searchTimesheet($search);
    public function getTimesheetsOfMembers();
    public function insightTimesheet();
}