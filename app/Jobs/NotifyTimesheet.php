<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\UserNotification;
use Mail;

class NotifyTimesheet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // list user
        $listReceiver = UserNotification::where("user_id", $this->user["userId"])->select("user_receive_id")->get();
        foreach ($listReceiver as $receiverId) {
            $email = $receiverId->info->email;
            $this->sendEmail($this->user["username"], $email);
        }
    }

    private function sendEmail($username, $address) 
    {
            $subject = $username . ' created timesheet. Please check !!!';
            $data = [
                "address" => $address,
                "subject" => $subject
            ];

            Mail::send('emails.create_timesheet',[], function($message) use ($data){
                $message->to($data["address"])->subject($data["subject"]);
            });
    }
}
