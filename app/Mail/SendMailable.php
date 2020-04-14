<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailable extends Mailable
{
    use Queueable, SerializesModels;
    protected $params;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (isset($this->params["username"])) {
            $subject = $this->params["username"] ." create time sheet";
            $view = "emails.create_timesheet";
        } else {
            $subject = "Please create your timesheet";
            $view = "emails.remind_user";
        }
        
        return $this->view($view)->subject($subject);
    }
}
