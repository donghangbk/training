<?php
namespace App\Services\Interfaces;
use Illuminate\Http\Request;
Interface TimesheetServiceInterface {
    public function listTimesheet();
    public function createTimesheet(Request $request);
    public function getDetail($id);
    public function member();
    public function updateTimesheet(Request $request, $id);
}