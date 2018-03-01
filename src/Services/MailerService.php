<?php

namespace App\Services;

use Mailgun\Mailgun;

/**
 * Created by PhpStorm.
 * User: ezequiel
 * Date: 28/02/2018
 * Time: 10:57
 */

class MailerService
{
    protected $mailgun;
    protected $domain;
    protected $from;

    public function __construct(Mailgun $mailgun,  $domain, $from)
    {
        $this->mailgun = $mailgun;
        $this->domain = $domain;
        $this->from = $from;
    }

    public function send($to, $subject, $text) {
        return $this->mailgun->messages()->send($this->domain, [
            'from' => $this->from,
            'to' => $to,
            'subject' => $subject,
            'text' => $text
        ]);
    }
}