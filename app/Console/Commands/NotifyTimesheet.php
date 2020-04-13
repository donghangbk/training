<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\Timesheet;
use Mail;

class NotifyTimesheet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:timesheet {params} {--queue=default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifications will be sent after creating or editing a timesheet';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $params = $this->argument('params');

        // list user
        $listEmail = UserNotification::where("user_id", $params["userId"])->join("users", "user_receive_id", "users.id")->select("email")->get();
        
        foreach ($listEmail as $email) {
            $address = $email["email"];
            $this->__sendEmail($params["username"], $address);
        }
    }

    private function __sendEmail($username, $address) {
            $subject = $username . ' created timesheet. Please check !!!';
            $data = [
                "address" => $address,
                "subject" => $subject
            ];

            Mail::queue('emails.create_timesheet',[], function($message) use ($data){
                $message->to($data["address"], 'HangDong')->subject($data["subject"]);
            });
    }
}
