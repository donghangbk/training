<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\Timesheet;
use Mail;

class RemindUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remind:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function handle() {
        $this->sendEmail();

    }

    public function sendEmail() {
        // foreach ($listEmail as $email) {
            Mail::send('emails.remind_user',[], function($message){
                $message->to("donhang.bk@gmail.com", 'Visitor')->subject('Please create your timesheet');
            });
        // }
    }
}
