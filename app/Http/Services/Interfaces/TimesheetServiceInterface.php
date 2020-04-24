<?php
namespace App\Services\Interfaces;
use Illuminate\Http\Request;
Interface TimesheetServiceInterface {
    public function listTimesheet();
    public function createTimesheet($request);
    public function getDetail($id);
    public function getTimesheetsOfMembers();
    public function updateTimesheet($request, $id);
    public function searchTimesheet($request);
}