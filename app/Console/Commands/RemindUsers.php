<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Mail\SendMailable;
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
    protected $description = 'Remind user to report time';

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
        $conditions = [["work_day", "=", date('Y-m-d')]];
        $listEmail = User::select("email")->whereNotIn("id", function($query) use ($conditions) {
            $query->select("user_id")->from("timesheets")->where($conditions);
        })->get();

        $this->sendEmail($listEmail);

    }

    private function sendEmail($listEmail) {
        foreach ($listEmail as $email) {
            $this->info("Sent email to: ". $email["email"]);
            $address = $email["email"];
            Mail::to($address)->queue(new SendMailable([]));
        }
    }
}
